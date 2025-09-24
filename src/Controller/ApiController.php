<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PaperRepository;

class ApiController extends AbstractController
{
 
 #[Route('/api/papers-by-method/{methodId}', name: 'papers_by_method')]
public function getPapersByMethod(int $methodId, PaperRepository $paperRepo): JsonResponse
{
    $papers = $paperRepo->findBy(['printingMethod' => $methodId]);
    
    $paperData = [];
    foreach ($papers as $paper) {
        $paperData[] = [
            'id' => $paper->getId(),
            'name' => $paper->getName(),
            'width' => $paper->getWidth(),
            'height' => $paper->getHeight()
        ];
    }
    
    return $this->json($paperData);
}
}