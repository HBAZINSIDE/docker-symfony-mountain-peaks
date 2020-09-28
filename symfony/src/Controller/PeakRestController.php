<?php

namespace App\Controller;

use App\Entity\Peak;
use App\Form\PeakType;
use App\Repository\PeakRepository;
use App\Service\PeakHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



/**
 * @Route("api/mountain/peaks")
 */
class PeakRestController extends AbstractController
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var PeakHelper
     */
    private $peakHelper;

    /**
     * PeakRestController constructor.
     * @param LoggerInterface $logger
     * @param PeakHelper $peakHelper
     */
    public function __construct(LoggerInterface $logger, PeakHelper $peakHelper)
    {
        $this->logger = $logger;
        $this->peakHelper = $peakHelper;
    }

    /**
     * Get a list of all existing peaks
     *
     * @Rest\View()
     * @Rest\Get("", name="peak_index_api")
     *
     * @SWG\Tag(name="Peak")
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * )
     *
     * @param PeakRepository $peakRepository
     * @return Response
     */
    public function indexAction(PeakRepository $peakRepository): Response
    {
        $this->logger->info('get all existing peaks');
        return $this->peakHelper->serialiseObject($peakRepository->findAll());
    }

    /**
     * Get peak's information from a given an ID
     *
     * @Rest\View()
     * @Rest\Get("/{id}", name="peak_show_api")
     *
     * @ParamConverter("peak", class="App\Entity\Peak",
     * options={"repository_method"="Find"})
     *
     * @SWG\Tag(name="Peak")
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(@Model(type=App\Entity\Peak::class))
     * )
     *
     * @param Peak $peak
     * @return Response
     */
    public function showAction(Peak $peak)
    {
        $this->logger->info('get peak with id : ' . $peak->getId());
        return $this->peakHelper->serialiseObject($peak);
    }

    /**
     * retrieve a list of peaks in a given geographical bounding box
     *
     * @Rest\View()
     * @Rest\Post("/inGeographicalBoundingBox", name="peaks_GBB_api")
     *
     * @SWG\Parameter(
     *     name="minLongitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="minLatitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="maxLongitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="maxLatitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Tag(name="Peak")
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function getPeaksInBoundingBoxAction(Request $request)
    {
        $this->logger->info('retrieve a list of peaks in a given geographical bounding box');
        return $this->peakHelper->getPeaksInBoundingBox($request->request->all());
    }


    /**
     * Add new Peak to DB.
     *
     * @Rest\View()
     * @Rest\Post("", name="peak_add_api")
     *
     * @SWG\Tag(name="Peak")
     * @SWG\Post(consumes={"application/x-www-form-urlencoded"})
     *
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="altitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="resource created successfully"
     * )
     *
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function addAction(Request $request): Response
    {
        $this->logger->info('creating peak');
        return $this->peakHelper->createPeak($request->request->all());
    }

    /**
     * Edit peak's information
     *
     * @Rest\View()
     * @Rest\Put("", name="peak_edit_api")
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     type="integer",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="altitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     type="number",
     *     required=true
     * )
     *
     * @SWG\Tag(name="Peak")
     * @SWG\Response(
     *     response=204,
     *     description="resource updated successfully")
     * )
     *
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function editAction(Request $request): Response
    {
        $this->logger->info('editing peak');
        return $this->peakHelper->editPeak($request->request->all());
    }

    /**
     * Delete Peak given an ID
     *
     * @Rest\View()
     * @Rest\Delete("/{id}", name="peak_delete_api")
     *
     * @ParamConverter("peak", class="App\Entity\Peak",
     * options={"repository_method"="Find"})
     *
     * @SWG\Tag(name="Peak")
     * @SWG\Response(
     *     response=204,
     *     description="resource deleted successfully")
     * )
     *
     * @param Peak $peak
     * @return Response
     */
    public function deleteAction(Peak $peak): Response
    {
        $this->logger->info('deleting peak');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($peak);
        $entityManager->flush();
        return new Response("resource deleted successfully", Response::HTTP_NO_CONTENT);
    }


}
