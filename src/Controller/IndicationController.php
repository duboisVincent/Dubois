<?php

namespace App\Controller;

use App\Entity\Indication;
use App\Form\IndicationType;
use App\Repository\IndicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/indication')]
class IndicationController extends AbstractController
{
    #[Route('/', name: 'app_indication_index', methods: ['GET'])]
    public function index(IndicationRepository $indicationRepository): Response
    {
        return $this->render('indication/index.html.twig', [
            'indications' => $indicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_indication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $indication = new Indication();
        $form = $this->createForm(IndicationType::class, $indication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($indication);
            $entityManager->flush();

            return $this->redirectToRoute('app_indication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('indication/new.html.twig', [
            'indication' => $indication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_indication_show', methods: ['GET'])]
    public function show(Indication $indication): Response
    {
        return $this->render('indication/show.html.twig', [
            'indication' => $indication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_indication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Indication $indication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IndicationType::class, $indication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_indication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('indication/edit.html.twig', [
            'indication' => $indication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_indication_delete', methods: ['POST'])]
    public function delete(Request $request, Indication $indication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$indication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($indication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_indication_index', [], Response::HTTP_SEE_OTHER);
    }
}
