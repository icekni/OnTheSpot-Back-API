<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom',
            ])
            ->add('description')
            ->add('price', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('availability', ChoiceType::class, [
                'label' => 'Disponible',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'title',
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // We need to check if it's a add or a edit action to put an attribute "required" on the input if necessary
                $product = $event->getData();
                $form = $event->getForm();

                // If $product is null, it's a add and the picture field is required
                if (null === $product->getId()) {
                    $form->add('picture', FileType::class,[
                        'label' => 'Photo',
                        'mapped' => false,
                        'required' => true,
                        'help' => 'Image principale du produit, affichée sur la page de detail du produit',
                        'constraints' => [
                            new NotBlank(),
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg'
                                ],
                            ])
                        ]
                    ])->add('thumbnail', FileType::class,[
                        'label' => 'Vignette',
                        'mapped' => false,
                        'required' => true,
                        'help' => 'Vignette du produit, affichée sur les listes de produits',
                        'constraints' => [
                            new NotBlank(),
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg'
                                ],
                            ])
                        ]
                    ]);
                } else {
                    // If it's a edit, the picture field is not required
                    $form->add('picture', FileType::class,[
                        'label' => 'Photo',
                        'mapped' => false,
                        'required' => false,
                        'help' => 'Image principale du produit, affichée sur la page de detail du produit',
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg'
                                ],
                            ])
                        ]
                    ])->add('thumbnail', FileType::class,[
                        'label' => 'Vignette',
                        'mapped' => false,
                        'required' => false,
                        'help' => 'Vignette du produit, affichée sur les listes de produits',
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg'
                                ],
                            ])
                        ]
                    ]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
