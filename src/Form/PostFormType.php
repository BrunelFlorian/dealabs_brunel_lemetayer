<?php

namespace App\Form;

use App\Entity\Deal;
use App\Entity\DealGroup;
use App\Repository\DealGroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    private $dealGroupRepository;

    public function __construct(DealGroupRepository $dealGroupRepository)
    {
        $this->dealGroupRepository = $dealGroupRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = $this->dealGroupRepository->findAll();

        $groupChoices = [];
        foreach ($groups as $group) {
            $groupChoices[$group->getName()] = $group->getId();
        }

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
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Tips' => 'tips',
                    'Coupon code' => 'coupon_code',
                ],
                'expanded' => true,
            ])
            ->add('dealGroup', EntityType::class, [
                'label' => 'Group',
                'class' => DealGroup::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
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
