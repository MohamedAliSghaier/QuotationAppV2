<?php

namespace App\Controller;


use App\Entity\Quotation;
use App\Form\QuotationType;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/quotation')]
#[IsGranted('ROLE_USER')]  // Only logged-in users can access
final class QuotationController extends AbstractController
{
    #[Route('/', name: 'quotation_index')]
    public function index(): Response
    {
        return $this->render('quotation/index.html.twig', [
            'controller_name' => 'QuotationController',
        ]);
    }
}
