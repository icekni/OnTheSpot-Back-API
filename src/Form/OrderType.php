<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\DeliveryPoint;
use App\Form\OrderProductType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deliveryTime', DateTimeType ::class, [
                'label' => 'Heure de livraison',
                'date_widget' => 'single_text'
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
            ->add('orderProducts', CollectionType::class, [
                'label' => '<h2>Détail de la commande</h2>',
                'label_html' => true,
                'entry_type' => OrderProductType::class,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => '--------------',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
