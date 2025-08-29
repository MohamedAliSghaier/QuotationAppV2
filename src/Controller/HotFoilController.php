<?php

namespace App\Controller;

use App\Entity\HotFoil;
use App\Form\HotFoilType;
use App\Repository\HotFoilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hot/foil')]
final class HotFoilController extends AbstractController
{
    #[Route(name: 'app_hot_foil_index', methods: ['GET'])]
    public function index(HotFoilRepository $hotFoilRepository): Response
    {
        return $this->render('hot_foil/index.html.twig', [
            'hot_foils' => $hotFoilRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hot_foil_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hotFoil = new HotFoil();
        $form = $this->createForm(HotFoilType::class, $hotFoil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hotFoil);
            $entityManager->flush();

            return $this->redirectToRoute('app_hot_foil_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hot_foil/new.html.twig', [
            'hot_foil' => $hotFoil,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hot_foil_show', methods: ['GET'])]
    public function show(HotFoil $hotFoil): Response
    {
        return $this->render('hot_foil/show.html.twig', [
            'hot_foil' => $hotFoil,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hot_foil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HotFoil $hotFoil, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HotFoilType::class, $hotFoil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_hot_foil_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hot_foil/edit.html.twig', [
            'hot_foil' => $hotFoil,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hot_foil_delete', methods: ['POST'])]
    public function delete(Request $request, HotFoil $hotFoil, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotFoil->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($hotFoil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hot_foil_index', [], Response::HTTP_SEE_OTHER);
    }
}
