<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Nom',
                    ]
            ])
            ->add('prix',IntegerType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Prix',
                    ]
            ])
            ->add('categorie',TextType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Categorie',
                    ]
            ])
            ->add('etat',TextType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Etat',
                    ]
            ])
            ->add('description',TextType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Description',
                    ]
            ])
            ->add('datePublication',DateType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Sujet',
                    ]
            ])
            ->add('dateVente',DateType::class,[
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Sujet',
                    ]
            ])
            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Sujet',
            ],
                'label' => 'Image ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                
            ])
            ->add('reservation',EntityType::class,[
                'class'=>Reservation::class,
                'attr' => ['class' => 'form-control' 
                    ]
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha',
                'attr' => ['class' => 'form-control' , 'placeholder' => 'Sujet',
            ],
              ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
