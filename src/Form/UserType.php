<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Prénom',
            'label_attr' => [
                'class' => 'form-label  mt-4'
            ],
          
        ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
             
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '7',
                    'maxLength' => '180'
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    
                ],
                'constraints' => [
                    new Assert\Length(['min' => 7, 'max' => 180]),
                    new Assert\Email(),
                    new Assert\NotBlank()
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control ms-4 mb-4 hidden'
                ],
                'multiple' => true,
                'choices'  => [
                    'Utilisateur' => 'ROLE_USER',
                    'Candidat' => 'ROLE_CANDIDATE',
                    'Recruteur' => 'ROLE_RECRUITER',
                    'Consultant' => 'ROLE_CONSULTANT',
                    // 'Admin' => 'ROLE_ADMIN'
                ]
            ])
            ->add('cvFilename', FileType::class, [
                'attr' => [
                    'class' => 'ms-4'
                ],
                'label' => 'CV (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Choisissez le document PDF valide',
                    ])
                ],
            ])
                
            ->add('plainPassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])

            ->add('company', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Companie',
                'label_attr' => [
                    'class' => 'form-label mt-4 text-dark fs-5',
                    'minLength' => '2',
                    'maxLength' => '255'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 255]),
                    
                ]
            ])
            
            ->add('location', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label mt-4 text-dark fs-5',
                    'minLength' => '2',
                    'maxLength' => '255'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 255]),
                   
                ]
            ])

            ->add('isVerfied', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input mt-4 mb-4',
                ],
                'required' => false,
                'label' => 'Demande vérifiée ? ',
                'mapped' => false,
                'label_attr' => [
                    'class' => 'form-check-label mt-3 ms-3 text-dark fs-5'
                ]
               
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                 'label' => 'Enregistrer'
        ])
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
