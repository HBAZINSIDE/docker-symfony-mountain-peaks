<?php


namespace App\Service;


use App\Entity\Peak;
use App\Repository\PeakRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PeakHelper
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PeakRepository
     */
    private $peakRepository;

    /**
     * PeakHelper constructor.
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param PeakRepository $peakRepository
     */
    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $entityManager, PeakRepository $peakRepository)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->peakRepository = $peakRepository;
    }

    /**
     * @param array $data
     * @return Response
     * @throws ORMException
     */
    public function createPeak(array $data): Response
    {
        // create peak from sent data
        $peak = new Peak($data['name'], $data['altitude'], $data['latitude'], $data['longitude']);
        // Validate sent data
        $errors = $this->validator->validate($peak);
        // if $peak is valid => store Peak in BD
        if (0 === count($errors)) {
            $this->entityManager->persist($peak);
            $this->entityManager->flush();
            return new Response("resource created successfully", Response::HTTP_CREATED);
        } else {
            // if not valid => return 500
            return new Response("non valid form", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * function used to send serialised json objects
     * @param $data
     * @return Response
     */
    public function serialiseObject($data): Response
    {
        $response = new Response($this->serializer->serialize($data,'json'),Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @param array $data
     * @return Response
     * @throws ORMException
     */
    public function editPeak(array $data): Response
    {
        // Get peak from DB
        $peak = $this->peakRepository->find($data['id']);
        // if peak exists in BD
        if ($peak) {
            $peak->setAltitude($data['altitude']);
            $peak->setName($data['name']);
            $peak->setLongitude($data['longitude']);
            $peak->setLatitude($data['latitude']);
            // Validate sent data
            $errors = $this->validator->validate($peak);
            // if $peak is valid => update Peak in BD
            if (0 === count($errors)) {
                $this->entityManager->persist($peak);
                $this->entityManager->flush();
                return new Response("resource updated successfully", Response::HTTP_NO_CONTENT);
            } else {
                // if null given (not valid) => return 500
                return new Response("non valid form", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return new Response("resource not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getPeaksInBoundingBox(array $data)
    {
        $result = $this->peakRepository->findInBoundaryBox($data);
        return $this->serialiseObject($result);
    }
}