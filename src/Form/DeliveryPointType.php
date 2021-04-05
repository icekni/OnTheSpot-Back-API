<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\DeliveryPoint;
use App\Repository\CityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DeliveryPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', null, [
                'label' => 'Coordonnées',
                'help' => 'Deplacez le marqueur sur la carte pour positionner le point de retrait'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeliveryPoint::class,
        ]);
    }
}
