<?php

namespace App\Controller;

use App\Entity\Peak;
use App\Form\PeakType;
use App\Repository\PeakRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("web/mountain/peaks")
 */
class PeakWebController extends AbstractController
{
    /**
     * @Route("/", name="peak_index", methods={"GET"})
     * @param PeakRepository $peakRepository
     * @return Response
     */
    public function index(PeakRepository $peakRepository): Response
    {
        return $this->render('peak/index.html.twig', [
            'peaks' => $peakRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="peak_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $peak = new Peak();
        $form = $this->createForm(PeakType::class, $peak);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($peak);
            $entityManager->flush();

            return $this->redirectToRoute('peak_index');
        }

        return $this->render('peak/new.html.twig', [
            'peak' => $peak,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="peak_show", methods={"GET"})
     * @param Peak $peak
     * @return Response
     */
    public function show(Peak $peak): Response
    {
        return $this->render('peak/show.html.twig', [
            'peak' => $peak,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="peak_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Peak $peak
     * @return Response
     */
    public function edit(Request $request, Peak $peak): Response
    {
        $form = $this->createForm(PeakType::class, $peak);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('peak_index');
        }

        return $this->render('peak/edit.html.twig', [
            'peak' => $peak,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="peak_delete", methods={"DELETE"})
     * @param Request $request
     * @param Peak $peak
     * @return Response
     */
    public function delete(Request $request, Peak $peak): Response
    {
        if ($this->isCsrfTokenValid('delete'.$peak->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($peak);
            $entityManager->flush();
        }

        return $this->redirectToRoute('peak_index');
    }
}
