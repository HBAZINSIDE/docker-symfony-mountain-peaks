<?php

namespace App\Repository;

use App\Entity\Peak;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Peak|null find($id, $lockMode = null, $lockVersion = null)
 * @method Peak|null findOneBy(array $criteria, array $orderBy = null)
 * @method Peak[]    findAll()
 * @method Peak[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeakRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Peak::class);
    }

    /**
     * @param array $data
     * @return int|mixed|string
     */
    public function findInBoundaryBox(array $data)
    {
        // find all peaks with minLongitude <= longitude <= maxLongitude
        // and minLatitude <= latitude <= maxLatitude
        $qb = $this->createQueryBuilder('p');
        return $qb->add('where',  $qb->expr()->andX(
            $qb->expr()->andX(
                $qb->expr()->gte('p.longitude', $data['minLongitude']),
                $qb->expr()->lte('p.longitude', $data['maxLongitude'])
            ),
            $qb->expr()->andX(
                $qb->expr()->gte('p.latitude', $data['minLatitude']),
                $qb->expr()->lte('p.latitude', $data['maxLatitude'])
            )
        ))
            ->getQuery()
            ->getResult();
    }

}
