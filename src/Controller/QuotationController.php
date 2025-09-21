<?php

namespace App\Controller;


use App\Entity\Quotation;
use App\Entity\Paper;
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
   // private int $gtoMaxWidth = 52; // store as number
    //private int $gtoMaxHeight = 36; // store as number

    #[Route('/', name: 'quotation_index')]
    public function index(QuotationRepository $quotationRepository): Response
    {
        // Show only quotations created by the current user
        $user = $this->getUser();
        $quotations = $quotationRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);
        
        return $this->render('quotation/index.html.twig', [
            'quotations' => $quotations,
        ]);
    }

#[Route('/calculate', name: 'quotation_calculate', methods: ['GET', 'POST'])]
public function calculate(Request $request): Response
{
    $quotation = new Quotation(); 
    $form = $this->createForm(QuotationType::class, $quotation);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData(); // this is a Quotation entity
     [$bestCut, $bestProducts] = $this->calculateOptimizedCut(
        $data->getWidth(),
        $data->getHeight(),
        $data->getPaper(),
        52,
        36
    );


  $request->getSession()->set('calculation_results', [
            'bestCut' => $bestCut,
            'bestProducts' => $bestProducts
        ]);
     
        // REQUIRED: Redirect after POST for Turbo
        return $this->redirectToRoute('quotation_calculate');

    }
 $results = $request->getSession()->get('calculation_results');
     $request->getSession()->remove('calculation_results');

    return $this->render('quotation/form.html.twig', [
        'form' => $form->createView(),
        'bestCut' => $results['bestCut'] ?? null,
        'bestProducts' => $results['bestProducts'] ?? null,
    ]);
}



    #[Route('/{quotationId}', name: 'test_cut_existing_quotation')]
public function testExistingQuotation(int $quotationId, EntityManagerInterface $em): Response
{
    // 1. Fetch the Quotation from the database
    $quotation = $em->getRepository(Quotation::class)->find($quotationId);

    if (!$quotation) {
        return new Response("Quotation with ID $quotationId not found.");
    }

    // 2. Call your optimized cutting function
    [$bestCut, $totalProducts] = $this->calculateOptimizedCut($quotation);
    [$sheetsNeeded , $totalPaperCost] = $this->calculateOffsetPaperPrice($quotation);
    [$bestProducts , $bestOrientation] = $this->calculateNumericPaperLayout($quotation);

    // 3. Display the results
    return new Response("
            cutWidthWithMargins: {$bestCut[0]}<br>
           cutHeightWithMargins: {$bestCut[1]}<br>
           actualCutWidth: {$bestCut[2]}<br>
           actualCutHeight: {$bestCut[3]}<br>
           cutOrientation: {$bestCut[4]}<br>
           productWidth: {$bestCut[5]}<br>
           productHeight: {$bestCut[6]}<br>
            totalProducts: $totalProducts<br>
            SheetsNeeded : $sheetsNeeded<br>
            totalPaperCost : $totalPaperCost

            

        ");
}





    

    private function calculateOptimizedCut(float $productWidth , float $productHeight  , Paper $paper , int $gtoMaxWdith = 52 , int $gtoMaxHeight = 36): array
    {
        $bestCut=null;
        $bestProducts = 0;
        $bestCutArea = 0;


       for ($cutWidth = min($productWidth, $productHeight); $cutWidth <= $gtoMaxWdith - 2; $cutWidth++) {
    for ($cutHeight = min($productWidth, $productHeight); $cutHeight <= $gtoMaxHeight - 2; $cutHeight++) 
            {
                $cutWidthWithMargins = $cutWidth + 2;
                $cutHeightWithMargins = $cutHeight + 2;
               
                foreach([[$productWidth,$productHeight] , [$productHeight,$productWidth]] as [$productWidth , $productHeight]){

                    if ($productWidth <= $cutWidth && $productHeight <= $cutHeight ) {

                      // Add 2cm (1cm each side)


                        $cuttingOptionA = intdiv($paper->getWidth(), $cutWidthWithMargins) * intdiv($paper->getHeight(), $cutHeightWithMargins);
                    
                        $cuttingOptionB = intdiv($paper->getWidth(), $cutHeightWithMargins) * intdiv($paper->getHeight(), $cutWidthWithMargins);

                       // $aFitsGTO = ($cutWidthWithMargins <= $gtoMaxWidth && $cutHeightWithMargins <= $gtoMaxHeight);
                      //  $bFitsGTO = ($cutHeightWithMargins <= $gtoMaxWidth && $cutWidthWithMargins <= $gtoMaxHeight);
                
                // Skip if NEITHER orientation fits
                    

              
                    
                    if ($cuttingOptionA >= $cuttingOptionB) {
                        $cutsFromSheet = $cuttingOptionA;
                        $actualCutWidth = $cutWidthWithMargins;   // Original orientation
                        $actualCutHeight = $cutHeightWithMargins;
                        $cutOrientation = 'original'; // 30×21 as planned
                    } else {
                        $cutsFromSheet = $cuttingOptionB;
                        $actualCutWidth = $cutHeightWithMargins;  // Swapped orientation  
                        $actualCutHeight = $cutWidthWithMargins;
                        $cutOrientation = 'rotated';  // 21×30 instead
                    }
                    
              
                       

                           if ($cutOrientation === 'original') {
                                    $productsPerCut = intdiv($cutWidth, $productWidth) * intdiv($cutHeight, $productHeight);
                         } else { // rotated
                                   $productsPerCut = intdiv($cutHeight, $productWidth) * intdiv($cutWidth, $productHeight);
                               }

                        $totalProducts = $productsPerCut * $cutsFromSheet ;
                        $currentCutArea = $actualCutWidth * $actualCutHeight;


                        $shouldUpdate = false;

                        if ($totalProducts > $bestProducts)
                        {
                            $shouldUpdate = true ;
                        }
                        elseif ($totalProducts === $bestProducts && $currentCutArea > $bestCutArea ){

                            $shouldUpdate = true ;
                        }

                        if ($shouldUpdate == true)
                        {
                            $bestProducts = $totalProducts;
                            $bestCut = [$cutWidthWithMargins , $cutHeightWithMargins , $actualCutWidth , $actualCutHeight , $cutOrientation , $productWidth, $productHeight];
                            $bestCutArea = $currentCutArea ;
                        }


                        
                    }
                }
            }
        }
        return [$bestCut , $bestProducts];
    }

    private function calculateOffsetPaperPrice(float $productWidth , float $productHeight , int $quantity , Paper $paper , int $gtoMaxWdith = 52 , int $gtoMaxHeight = 36): array
    {
            [, $productsPerLargeSheet] = $this->calculateOptimizedCut($productWidth , $productHeight  , $paper , $gtoMaxWidth , $gtoMaxHeight);
            if ($productsPerLargeSheet === 0) {
            throw new \RuntimeException("No valid cutting layout for this quotation (ID {$quotation->getId()}).");
               }
            $sheetsNeeded = ceil($quotation->getQuantity() / $productsPerLargeSheet);
            $totalPaperCost = $sheetsNeeded * $quotation->getPaper()->getPricePerSheet();
            return[$sheetsNeeded , $totalPaperCost];
    }

    private function calculateNumericPaperLayout(Quotation $quotation): array
    {    
          $bestProducts = 0;
          $bestOrientation = 'original';
          $usablePaperWidth = $quotation->getPaper()->getWidth() - 2 ;
          $usablePaperHeight = $quotation->getPaper()->getHeight() - 2 ;
          foreach([[$quotation->getWidth(),$quotation->getHeight()] , [$quotation->getHeight(),$quotation->getWidth()]] as $index => [$productWidth , $productHeight]){

             if ($productWidth <= $usablePaperWidth && $productHeight <= $usablePaperHeight ) {


              $totalProducts = intdiv($usablePaperWidth, $productWidth) * intdiv($usablePaperHeight, $productHeight);
             
             

              if ($totalProducts > $bestProducts)
                {
                   $bestProducts = $totalProducts;
                   $bestOrientation = $index === 0 ? 'original' : 'rotated';
                 

               }
              }
             }

            
             return [
                 $bestProducts,
                $bestOrientation
             ];

    }
        private function calculateNumericPaperPrice(Quotation $quotation): array
    {
            [,$productsPerNumericSheet] = $this->calculateNumericPaperLayout($quotation);
            if ($$productsPerNumericSheet === 0) {
            throw new \RuntimeException("No valid cutting layout for this quotation (ID {$quotation->getId()}).");
               }
            $sheetsNeeded = ceil($quotation->getQuantity() / $productsPerNumericSheet);
            $totalPaperCost = $sheetsNeeded * $quotation->getPaper()->getPricePerSheet();
            return[$sheetsNeeded , $totalPaperCost];
    }

}
