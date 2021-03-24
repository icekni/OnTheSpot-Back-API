<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deliveryTime')
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Non acceptée' => 0,
                    'Non acceptée' => 0,
                    'Non acceptée' => 0,
                    'Non acceptée' => 0,
                ]
            ])
            ->add('createdAt')
            ->add('deliveryPoint')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
