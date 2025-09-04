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
    private int $gtoMaxWidth = 52; // store as number
    private int $gtoMaxHeight = 36; // store as number

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

    // 3. Display the results
    return new Response("
            Best Cut:<br>
            Cut Width: {$bestCut[0]}<br>
            Cut Height: {$bestCut[1]}<br>
            Actual Cut Width: {$bestCut[2]}<br>
            Actual Cut Height: {$bestCut[3]}<br>
            Orientation : {$bestCut[4]}<br>
            Product Width: {$bestCut[5]}<br>
            Product Height: {$bestCut[6]}<br>
            Total Products: $totalProducts<br>
             Paper Width: " . $quotation->getPaper()->getWidth() . "<br>
    Paper Height: " . $quotation->getPaper()->getHeight() . "
        ");
}


    

    private function calculateOptimizedCut(Quotation $quotation): array
    {
        $bestCut=null;
        $bestProducts = 0;
        $bestCutArea = 0;


        for ($cutWidth = $quotation->getWidth() ; $cutWidth <= $this->gtoMaxWidth ; $cutWidth++){
            for ($cutHeight = $quotation->getHeight() ; $cutHeight <= $this->gtoMaxHeight ; $cutHeight++ )
            {
                foreach([[$quotation->getWidth(),$quotation->getHeight()] , [$quotation->getHeight(),$quotation->getWidth()]] as [$productWidth , $productHeight]){

                    if ($productWidth <= $cutWidth && $productHeight <= $cutHeight ) {

                        $cuttingOptionA = intdiv($quotation->getPaper()->getWidth(), $cutWidth) * intdiv($quotation->getPaper()->getHeight(), $cutHeight);
                    
                        $cuttingOptionB = intdiv($quotation->getPaper()->getWidth(), $cutHeight) * intdiv($quotation->getPaper()->getHeight(), $cutWidth);
                    
                    // Determine which cutting orientation is better
                    if ($cuttingOptionA >= $cuttingOptionB) {
                        $cutsFromSheet = $cuttingOptionA;
                        $actualCutWidth = $cutWidth;   // Original orientation
                        $actualCutHeight = $cutHeight;
                        $cutOrientation = 'original'; // 30×21 as planned
                    } else {
                        $cutsFromSheet = $cuttingOptionB;
                        $actualCutWidth = $cutHeight;  // Swapped orientation  
                        $actualCutHeight = $cutWidth;
                        $cutOrientation = 'rotated';  // 21×30 instead
                    }

                           $productsPerCut = intdiv($actualCutWidth , $productWidth) * intdiv($actualCutHeight , $productHeight);

                        $totalProducts = $productsPerCut * $cutsFromSheet ;
                        $currentCutArea = $cutWidth * $cutHeight;


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
                            $bestCut = [$cutWidth , $cutHeight , $actualCutWidth , $actualCutHeight , $cutOrientation , $productWidth, $productHeight];
                            $bestCutArea = $currentCutArea ;
                        }


                        
                    }
                }
            }
        }
        return [$bestCut , $bestProducts];
       /* $basePrice = 0;
        
        // Required items (always exist)
        $basePrice += (float) $quotation->getPaper()->getPrice();
        $basePrice += (float) $quotation->getPrintingMethod()->getPrice();
        
        // Optional items (only if selected)
        if ($quotation->getLamination()) {
            $basePrice += (float) $quotation->getLamination()->getPrice();
        }
        if ($quotation->getCorners()) {
            $basePrice += (float) $quotation->getCorners()->getPrice();
        }
        if ($quotation->getFolding()) {
            $basePrice += (float) $quotation->getFolding()->getPrice();
        }
        if ($quotation->getHotFoil()) {
            $basePrice += (float) $quotation->getHotFoil()->getPrice();
        }
        
        // Multiply by quantity
        $totalPrice = $basePrice * $quotation->getQuantity();
        
        // Add your custom logic here:
        // - Volume discounts
        // - Special pricing rules
        // - Additional fees
        // - Minimum order amounts
        // Example:
        if ($quotation->getQuantity() > 100) {
            $totalPrice *= 0.9; // 10% discount for large orders
        }
        
        return number_format($totalPrice, 2, '.', '');*/
    }
}
