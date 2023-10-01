<?php

namespace App\Controller\Admin;

use App\Entity\Apply;
use App\Entity\Contact;
use App\Entity\Job;
use App\Entity\User;
use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
    
 #[IsGranted('ROLE_ADMIN')]
 #[Route('/job', name: 'job.edit', methods: ['GET', 'POST'])]
 public function editJob(JobRepository $jobRepository): Response
 {
    return $this->render('job/edit.html.twig');
    
 }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToRoute('Voir le site', 'fas fa-list', 'home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class)->setPermission('ROLE_CONSULTANT');
        yield MenuItem::linkToCrud('Annonces', 'fas fa-seedling', Job::class)->setPermission('ROLE_CONSULTANT');
        yield MenuItem::linkToCrud('Postulés', 'fas fa-seedling', Apply::class)->setPermission('ROLE_CONSULTANT');
    
      
    }
   
}
