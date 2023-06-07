<?php

namespace App\Form;

use App\Entity\Deal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('link', TextType::class, [
            //     'label' => 'Deal Link'
            // ])
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
            ]);
            // ->add('image', FileType::class, [
            //     'label' => 'Image',
            //     'required' => false,
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deal::class,
        ]);
    }
}
