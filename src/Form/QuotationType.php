<?php

namespace App\Form;

use App\Entity\Corners;
use App\Entity\Folding;
use App\Entity\HotFoil;
use App\Entity\Lamination;
use App\Entity\Paper;
use App\Entity\PrintingMethod;
use App\Entity\Quotation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          /*  ->add('clientName', TextType::class, [
                'label' => 'Client Name *',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])*/

            ->add('width', NumberType::class, [
            'label' => 'Width *',
            'required' => true,
            'scale' => 2,
            'attr' => [
                'class' => 'form-control',
                'step' => '0.01',
                'min' => '0.01',
                'placeholder' => 'e.g. 21.0'
            ],
        ])
        ->add('height', NumberType::class, [
            'label' => 'Height *',
            'required' => true,
            'scale' => 2,
            'attr' => [
                'class' => 'form-control',
                'step' => '0.01',
                'min' => '0.01',
                'placeholder' => 'e.g. 29.7'
            ],
        ])
        
            ->add('paper', EntityType::class, [
                'class' => Paper::class,
                'choice_label' => function(Paper $paper) {
                    return $paper->getName() . ' - $' . $paper->getPricePerSheet();
                },
                'placeholder' => 'Choose a paper type *',
                'required' => true,
                'attr' => ['class' => 'form-select'],
            ])

        /*    ->add('printingMethod', EntityType::class, [
                'class' => PrintingMethod::class,
                'choice_label' => function(PrintingMethod $method) {
                    return $method->getName() . ' - $' . $method->getSetupCost();
                },
                'placeholder' => 'Choose printing method *',
                'required' => true,
                'attr' => ['class' => 'form-select'],
            ])

            
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'value' => 1
                ],
            ])
              ->add('lamination', EntityType::class, [
                'class' => Lamination::class,
                'choice_label' => function(Lamination $lamination) {
                    return $lamination->getName() . ' - $' . $lamination->getPrice();
                },
                'placeholder' => 'No lamination (optional)',
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
        
        
            ->add('corners', EntityType::class, [
                'class' => Corners::class,
                'choice_label' => function(Corners $corners) {
                    return $corners->getName() . ' - $' . $corners->getPrice();
                },
                'placeholder' => 'No corner treatment (optional)',
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('folding', EntityType::class, [
                'class' => Folding::class,
                'choice_label' => function(Folding $folding) {
                    return ' - $' . $folding->getPrice();
                },
                'placeholder' => 'No folding (optional)',
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('hotFoil', EntityType::class, [
                'class' => HotFoil::class,
                'choice_label' => function(HotFoil $hotFoil) {
                    return $hotFoil->getName() . ' - $' . $hotFoil->getPrice();
                },
                'placeholder' => 'No hot foil (optional)',
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])

            ->add('notes', TextareaType::class, [
                'label' => 'Additional Notes',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Any special requirements or notes...'
                ],
            ])*/

            ->add('submit', SubmitType::class, [
                'label' => 'Create Quotation',
                'attr' => ['class' => 'btn btn-primary btn-lg'],
            ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quotation::class,
        ]);
    }
}
