<?php

namespace App\Controller;

use App\Entity\Folding;
use App\Form\FoldingType;
use App\Repository\FoldingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/folding')]
final class FoldingController extends AbstractController
{
    #[Route(name: 'app_folding_index', methods: ['GET'])]
    public function index(FoldingRepository $foldingRepository): Response
    {
        return $this->render('folding/index.html.twig', [
            'foldings' => $foldingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_folding_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $folding = new Folding();
        $form = $this->createForm(FoldingType::class, $folding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($folding);
            $entityManager->flush();

            return $this->redirectToRoute('app_folding_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('folding/new.html.twig', [
            'folding' => $folding,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_folding_show', methods: ['GET'])]
    public function show(Folding $folding): Response
    {
        return $this->render('folding/show.html.twig', [
            'folding' => $folding,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_folding_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Folding $folding, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FoldingType::class, $folding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_folding_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('folding/edit.html.twig', [
            'folding' => $folding,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_folding_delete', methods: ['POST'])]
    public function delete(Request $request, Folding $folding, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$folding->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($folding);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_folding_index', [], Response::HTTP_SEE_OTHER);
    }
}
