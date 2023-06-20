<?php

namespace App\Form;

use App\Entity\Alert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class AlertFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('keyword', TextType::class, [
            'label' => 'Keyword',
        ])
        ->add('minTemperature', IntegerType::class, [
            'label' => 'Minimal temperature',
            'attr' => [
                'min' => 0,
            ],
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 0,
                    'message' => 'The minimal temperature should be greater than or equal to 0.',
                ]),
            ],
        ])
        ->add('notificationFrequency', ChoiceType::class, [
            'label' => 'Notification frequency',
            'choices' => [
                'Every day' => 'daily',
                'Every week' => 'weekly',
                'Every month' => 'monthly',
            ],
        ])
        ->add('emailNotificationEnabled', CheckboxType::class, [
            'label' => 'Enable email notification',
            'required' => false,
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Save alert',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alert::class,
        ]);
    }
}
