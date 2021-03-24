<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\DeliveryPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deliveryTime', TimeType::class, [
                'label' => 'Heure de livraison',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => 0,
                    'En préparation' => 1,
                    'En livraison' => 2,
                    'Livrée' => 3,
                ]
            ])
            ->add('deliveryPoint', EntityType::class, [
                'label' => 'Point de RDV',
                'class' => DeliveryPoint::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
