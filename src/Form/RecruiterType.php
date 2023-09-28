<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RecruiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                new Assert\NotBlank()
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
                new Assert\NotBlank()
            ]
        ])

        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ],
             'label' => 'Enregistrer'
    ])
        ;
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
