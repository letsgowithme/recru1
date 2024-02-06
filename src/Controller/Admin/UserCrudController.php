<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
    return $crud
                ->setEntityLabelInPlural('Utilisateurs')
                ->setEntityLabelInSingular('Utilisateur')
                ->setSearchFields(['lastname'])
                ->setPageTitle("index", "Administration des utilisateurs")
                ->setDefaultSort(['id' => 'asc'])
                ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Utilisateurs')
                ->setPageTitle(pageName:Crud::PAGE_NEW, title: 'Créer un Utilisateur')
                ->setPageTitle(pageName:Crud::PAGE_EDIT, title: 'Modifier l\'Utilisateur')
    ;
    }
    
 
    
    public function configureFields(string $pageName): iterable
    {
         $roles = ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_CANDIDATE', 'ROLE_RECRUITER', 'ROLE_USER'];
    return [
    IdField::new('id')
    ->hideOnForm(),
    TextField::new('lastname')
    ->setLabel('Nom'),
    TextField::new('firstname')
    ->setLabel('Prénom'),
    TextField::new('email'),
    ImageField::new('cvFilename')
    ->setFormType(FileUploadType::class)
    ->setUploadDir('/public/uploads/cv')
    ->setBasePath('/uploads/cv')
    ->setRequired(false)
    ->hideOnIndex()
    ->setFormTypeOptions(['attr' => [
        'accept' => 'application/pdf'
    ]
    ])
    ->setLabel('CV'),
    TextField::new('plainPassword', 'password')
        ->setFormType(PasswordType::class)
        ->setRequired($pageName === Crud::PAGE_NEW)
        ->onlyOnForms()
        ->setLabel('Mot de passe'),
    TextField::new('company')
        ->setLabel('Company'),
    TextField::new('location')
        ->setLabel('Adresse')
        ->hideOnIndex(),
    ArrayField::new('roles')
            ->setLabel('Rôle'),   
    DateTimeField::new('createdAt')
            ->hideOnForm()
            ->setFormTypeOption('disabled', 'disabled'),
    BooleanField::new('isVerified')
             ->setLabel('Approuvé ?')
    ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }
    
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }
    private UserPasswordHasherInterface $hasher;
    
    public function __construct(UserPasswordHasherInterface $hasher) 
    {
    $this->hasher = $hasher;
    }
    public function prePersist(User $user) {
    $this->encodePassword($user);
    }
    
    public function preUpdate(User $user) {
    $this->encodePassword($user);
    }
    /**
    * 
    * Encode password based on plain password
    *
    * @param User $user
    * @return void
    */
    private function encodePassword(User $user)
    {
        if ($user->getPlainPassword() !== null) {
            $user->setPassword(
                $this->hasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
       
       )
       );
       $user->setPlainPassword(null);
       }
    
        }
    }
    
    