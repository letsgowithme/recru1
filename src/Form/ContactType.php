<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('lastname', TextType::class, [
'attr' => [
'class' => 'form-control',
'minlenght' => '2',
'maxlenght' => '50',
],
'label' => 'Nom / Prénom',
'label_attr' => [
'class' => 'form-label mt-4 text-dark fs-5'
],
'constraints' => [
new Assert\NotBlank(),
new Assert\Length(['min' => 2, 'max' => 50])
]
])

->add('email', EmailType::class, [
'attr' => [
'class' => 'form-control',
'minLength' => '2',
'maxLength' => '180'
],
'label' => 'Email',
'label_attr' => [
'class' => 'form-label mt-4 text-dark fs-5'

],
'constraints' => [
new Assert\Length(['min' => 2, 'max' => 180]),
new Assert\Email(),
new Assert\NotBlank()
]
])

->add('subject', TextType::class, [
'attr' => [
'class' => 'form-control'
],
'label' => 'Sujet',
'label_attr' => [
'class' => 'form-label mt-4 text-dark fs-5'
],
'constraints' => [
new Assert\NotBlank(),
new Assert\Length(['min' => 2, 'max' => 100])
]
])

->add('message', TextareaType::class, [
'attr' => [
'class' => 'form-control'
],
'label' => 'Message',
'label_attr' => [
'class' => 'form-label mt-4 text-dark fs-5'
],
'constraints' => [
new Assert\NotBlank()
]
])
->add('submit', SubmitType::class, [
'attr' => [
'class' => 'btn btn-primary mt-4'
],
'label' => 'Envoyer'
])
;
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
// Configure your form options here
]);
}
}


