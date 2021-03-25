<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // We need to check if it's an add or an edit action to put an attribute "required" on the input if necessary
                $category = $event->getData();
                $form = $event->getForm();

                // If $category is null, it's a add and the picture field is required
                if (null === $category->getId()) {
                    $form->add('picture', FileType::class,[
                        'label' => 'Photo',
                        'mapped' => false,
                        'required' => true,
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
            'data_class' => Category::class,
        ]);
    }
}
