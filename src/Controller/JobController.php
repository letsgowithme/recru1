<?php

namespace App\Controller;

use App\Entity\Apply;
use App\Entity\Job;
use App\Form\ApplyType;
use App\Form\JobType;
use App\Repository\ApplyRepository;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/job')]
class JobController extends AbstractController
{
    /**
     * Show all the posts of job for everybody
     * @param JobRepository $jobRepository
     * @return Response
     */
    #[Route('/', name: 'job.index', methods: ['GET'])]
    public function all(JobRepository $jobRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $jobRepository->paginationQuery(),
            $request->query->get('page', 1),
            5

        );
        return $this->render('job/index.html.twig', [
        //    'jobs' => $jobs,
           'pagination' => $pagination
        ]);
    }
    
     /**
     * Show the jobs if user is connected
     * @param JobRepository $jobRepository
     * @return Response
     */
   
   
     #[Route('/annonces', name: 'job.annonces', methods: ['GET'])]
     #[IsGranted('ROLE_RECRUITER')]
     public function annonces(JobRepository $repository): Response
     {
       $jobs =  $repository->findBy(['author' => $this->getUser()]);

         return $this->render('job/annonces.html.twig', [
             'jobs' => $jobs,
             'author' => $this->getUser(),
         ]);
     }
    
 /**
     * This function creates a job
     * @param Job $job
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_RECRUITER')]
    #[Route('/new', name: 'job.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager,
    UserInterface $user,): Response
    {
        $job = new Job();
        // if($user) {
        //     $user = $job->getAuthor();
        //  if($this->getUser()) {
        //     $job->setAuthor($this->getUser());
        //  } 
          
        
        // }
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $jobRepository->save($job, true);
            $job = $form->getData();
            $job->setAuthor($this->getUser());

            $manager->persist($job);
            $manager->flush();


            return $this->redirectToRoute('job.annonces', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            // 'job_annonce' => $job_annonce,
            'form' => $form->createView(),
            
        ]);
    }

     /**
      * Show a job
      * @param Job $job
      * @param Request $request
      * @param EntityManagerInterface $manager
      * @return Response
      */
 
      #[Route('/{id}', name: 'job.show',methods: ['GET', 'POST'])]
      public function show(
          Job $job,
          Request $request,
          EntityManagerInterface $manager,
      ): Response {

        $apply = new Apply();
        $apply->setJob($job);       
          if ($this->getUser()) {
              $apply->setCandidate($this->getUser());
        }
       
        $formApply = $this->createForm(ApplyType::class, $apply); 
        $formApply->handleRequest($request);
        $error = $formApply->getErrors();
  
          /* form Apply */
  
          if ($formApply->isSubmitted() && $formApply->isValid()) {
                 $apply->setCandidate($this->getUser())
                       ->setJob($job);
            
              $manager->persist($apply);
              $manager->flush();
              $this->addFlash(
                  'success',
                  'Vous avez postulé pour cette annonce'
              );
              return $this->redirectToRoute('job.show', ['id' => $job->getId()]);
          }
          return $this->render('job/show.html.twig', [
              'job' => $job,
              'formApply' => $formApply->createView(),
              'error' => $error
          ]);
      }


     /**
     * This function edits the job
     * @param Job $job
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_RECRUITER')]
    #[Route('/{id}/edit', name: 'job.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, 
    Job $job, 
    JobRepository $repository): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($job, true);

            return $this->redirectToRoute('job.show', ['id' => $job->getId()]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

     /**
     * This function deletes the job
     * @param Job $job
     * @param Request $request
     * @return Response
     */
    // #[IsGranted('ROLE_RECRUITER')]
    #[Route('/{id}', name: 'job.delete', methods: ['POST'])]
    public function delete(Request $request, Job $job, JobRepository $jobRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $jobRepository->remove($job, true);
        }

        return $this->redirectToRoute('job.index', [], Response::HTTP_SEE_OTHER);
    }
}
