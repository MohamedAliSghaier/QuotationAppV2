<?php

namespace App\Controller;

use App\Entity\PrintingMethod;
use App\Form\PrintingMethodType;
use App\Repository\PrintingMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/printing/method')]
final class PrintingMethodController extends AbstractController
{
    #[Route(name: 'app_printing_method_index', methods: ['GET'])]
    public function index(PrintingMethodRepository $printingMethodRepository): Response
    {
        return $this->render('printing_method/index.html.twig', [
            'printing_methods' => $printingMethodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_printing_method_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $printingMethod = new PrintingMethod();
        $form = $this->createForm(PrintingMethodType::class, $printingMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($printingMethod);
            $entityManager->flush();

            return $this->redirectToRoute('app_printing_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('printing_method/new.html.twig', [
            'printing_method' => $printingMethod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_printing_method_show', methods: ['GET'])]
    public function show(PrintingMethod $printingMethod): Response
    {
        return $this->render('printing_method/show.html.twig', [
            'printing_method' => $printingMethod,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_printing_method_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PrintingMethod $printingMethod, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrintingMethodType::class, $printingMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_printing_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('printing_method/edit.html.twig', [
            'printing_method' => $printingMethod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_printing_method_delete', methods: ['POST'])]
    public function delete(Request $request, PrintingMethod $printingMethod, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$printingMethod->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($printingMethod);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_printing_method_index', [], Response::HTTP_SEE_OTHER);
    }
}
