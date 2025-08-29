<?php

namespace App\Controller;

use App\Entity\Lamination;
use App\Form\LaminationType;
use App\Repository\LaminationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lamination')]
final class LaminationController extends AbstractController
{
    #[Route(name: 'app_lamination_index', methods: ['GET'])]
    public function index(LaminationRepository $laminationRepository): Response
    {
        return $this->render('lamination/index.html.twig', [
            'laminations' => $laminationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lamination_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lamination = new Lamination();
        $form = $this->createForm(LaminationType::class, $lamination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lamination);
            $entityManager->flush();

            return $this->redirectToRoute('app_lamination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lamination/new.html.twig', [
            'lamination' => $lamination,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lamination_show', methods: ['GET'])]
    public function show(Lamination $lamination): Response
    {
        return $this->render('lamination/show.html.twig', [
            'lamination' => $lamination,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lamination_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lamination $lamination, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LaminationType::class, $lamination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lamination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lamination/edit.html.twig', [
            'lamination' => $lamination,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lamination_delete', methods: ['POST'])]
    public function delete(Request $request, Lamination $lamination, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lamination->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lamination);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lamination_index', [], Response::HTTP_SEE_OTHER);
    }
}
