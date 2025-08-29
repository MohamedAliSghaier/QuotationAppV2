<?php

namespace App\Controller;

use App\Entity\Corners;
use App\Form\CornersType;
use App\Repository\CornersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/corners')]
final class CornersController extends AbstractController
{
    #[Route(name: 'app_corners_index', methods: ['GET'])]
    public function index(CornersRepository $cornersRepository): Response
    {
        return $this->render('corners/index.html.twig', [
            'corners' => $cornersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_corners_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $corner = new Corners();
        $form = $this->createForm(CornersType::class, $corner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($corner);
            $entityManager->flush();

            return $this->redirectToRoute('app_corners_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('corners/new.html.twig', [
            'corner' => $corner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_corners_show', methods: ['GET'])]
    public function show(Corners $corner): Response
    {
        return $this->render('corners/show.html.twig', [
            'corner' => $corner,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_corners_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Corners $corner, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CornersType::class, $corner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_corners_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('corners/edit.html.twig', [
            'corner' => $corner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_corners_delete', methods: ['POST'])]
    public function delete(Request $request, Corners $corner, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$corner->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($corner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_corners_index', [], Response::HTTP_SEE_OTHER);
    }
}
