<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options['create']==true)
        {
            $builder
                ->add('title', TextType::class,[
                    'required'=>false,
                    'label'=>'Titre de l\'oeuvre',
                    'attr'=>[
                        'placeholder'=>'Saisissez le titre de l\'oeuvre'
                    ]

                ])
                ->add('picture_src', FileType::class,[
                    'required'=>false,
                    'label'=>'Fichier photo à charger',
                    'constraints'=>[
                        new File(['mimeTypes'=>[
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                            'mimeTypesMessage'=>'Les formats autorisés sont: jpg, jpeg, png '])
                    ]

                ])
                ->add('picture_name', TextType::class,[
                    'required'=>false,
                    'label'=>'Titre du fichier photo',
                    'attr'=>[
                        'placeholder'=>'Saisissez le titre du fichier photo'
                    ]

                ])
                ->add('price', NumberType::class,[
                    'required'=>false,
                    'label'=>'Prix de l\'oeuvre',
                    'attr'=>[
                        'placeholder'=>'Saisissez le Prix de l\'oeuvre'
                    ]

                ])
                ->add('description', TextareaType::class,[
                    'required'=>false,
                    'label'=>'Description de l\'oeuvre',

                ])
                ->add('categories', EntityType::class,[
                    'class'=>Category::class,
                    'label'=>"Catégories",
                    'choice_label'=>'title',
                    'multiple'=>true,
                    'placeholder'=>'Saisissez les catégories en liens avec l\'oeuvre',
                    'attr'=>[
                        'class'=>'select2'
                    ]

                ])
                ->add('Valider', SubmitType::class)
            ;



        }else{

            $builder
                ->add('title', TextType::class,[
                    'required'=>false,
                    'label'=>'Titre de l\'oeuvre',
                    'attr'=>[
                        'placeholder'=>'Saisissez le titre de l\'oeuvre'
                    ]

                ])
                ->add('update_picture_src', FileType::class,[
                    'required'=>false,
                    'label'=>'Fichier photo à charger',
                    'constraints'=>[
                        new File(['mimeTypes'=>[
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                            'mimeTypesMessage'=>'Les formats autorisés sont: jpg, jpeg, png '])
                    ]

                ])
                ->add('picture_name', TextType::class,[
                    'required'=>false,
                    'label'=>'Titre du fichier photo',
                    'attr'=>[
                        'placeholder'=>'Saisissez le titre du fichier photo'
                    ]

                ])
                ->add('price', NumberType::class,[
                    'required'=>false,
                    'label'=>'Prix de l\'oeuvre',
                    'attr'=>[
                        'placeholder'=>'Saisissez le Prix de l\'oeuvre'
                    ]

                ])
                ->add('description', TextareaType::class,[
                    'required'=>false,
                    'label'=>'Description de l\'oeuvre',

                ])
                ->add('categories', EntityType::class,[
                    'class'=>Category::class,
                    'label'=>"Catégories",
                    'choice_label'=>'title',
                    'multiple'=>true,
                    'placeholder'=>'Saisissez les catégories en liens avec l\'oeuvre',
                    'attr'=>[
                        'class'=>'select2'
                    ]

                ])
                ->add('Valider', SubmitType::class)
            ;




        }



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'update'=>false,
            'create'=>false
        ]);
    }
}
