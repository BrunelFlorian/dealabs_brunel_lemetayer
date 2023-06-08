<?php

namespace App\Form;

use App\Entity\Deal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'EUR',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('expirationDate', DateTimeType::class, [
                'label' => 'Expiration Date',
                'widget' => 'single_text',
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Tips' => 'tips',
                    'Coupon code' => 'coupon_code',
                ],
                'expanded' => true, // Set to true if you want radio buttons instead of a dropdown select
                // 'multiple' => false, // Set to true if you want to allow selecting multiple categories
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deal::class,
        ]);
    }
}
