<?php

namespace App\Controller\Admin;

use App\Entity\Apply;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ApplyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Apply::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
    return $crud
                ->setSearchFields(['lastname'])
                ->setPageTitle(pageName: Crud::PAGE_INDEX, title: 'Postulé')
               
                ->setPageTitle(pageName: Crud::PAGE_EDIT, title: 'Modifier la demande');
               
    ;
    }
    
  
    public function configureFields(string $pageName): iterable
    {
        return [
           
           
            AssociationField::new('job')
            ->setLabel('Offre'),
            AssociationField::new('candidate')
                ->setLabel('Candidat'), 
            // AssociationField::new('notifications')
            //     ->setLabel('Notification'), 
            BooleanField::new('isApproved')
                ->setLabel('Approuvé ?')
          
        ];
    }
    
}
