<?php

namespace App\Form;

use App\Entity\Paper;
use App\Entity\PrintingMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class PaperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('width')
            ->add('height')
            ->add('weight')
            ->add('price_per_sheet')
            ->add('printingMethod', EntityType::class, [
                'class' => PrintingMethod::class,
                'choice_label' => 'name', // the field shown in the dropdown
                'placeholder' => 'Select a printing method',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paper::class,
        ]);
    }
}
