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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientName')
            ->add('quantity')
            ->add('totalPrice')
            ->add('status')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('notes')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('paper', EntityType::class, [
                'class' => Paper::class,
                'choice_label' => 'id',
            ])
            ->add('printingMethod', EntityType::class, [
                'class' => PrintingMethod::class,
                'choice_label' => 'id',
            ])
            ->add('lamination', EntityType::class, [
                'class' => Lamination::class,
                'choice_label' => 'id',
            ])
            ->add('corners', EntityType::class, [
                'class' => Corners::class,
                'choice_label' => 'id',
            ])
            ->add('folding', EntityType::class, [
                'class' => Folding::class,
                'choice_label' => 'id',
            ])
            ->add('hotFoil', EntityType::class, [
                'class' => HotFoil::class,
                'choice_label' => 'id',
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
