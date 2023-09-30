<?php

namespace App\Controller;

use App\Entity\Apply;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ApplyRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;


class ApplyController extends AbstractController
{
    #[Route('/apply', name: 'apply.index', methods: ['GET'])]
    #[IsGranted('ROLE_RECRUITER')]
    public function index(ApplyRepository $applyRepository,
    Request $request,
    EntityManagerInterface $manager,
    MailerInterface $mailer
    ): Response
    {
              $applies = $applyRepository->findAll();
            //   $applies = $applyRepository->findBy(['isApproved' => true]);
              // $appliesAuthor = "";
              
              $emailSender = "admin@exemple.com";

            //   if($this->getParameter('isApproved' == true)){
            //   if($applies){
                 foreach ($applies as $apply) {
                   $emailRecru = 'admin@exemple.com';
                   $emailRecru = $apply->getJob()->getAuthor()->getEmail();

                   $applyCandidateLastname = $apply->getCandidate()->getLastname();
                   $applyCandidateFirstname = $apply->getCandidate()->getFirstname();
                   $applyCandidateEmail = $apply->getCandidate()->getEmail();
                   $applyCandidateCv = $apply->getCandidate()->getCvFilename();
                 //Email
                 if($apply->getIsApproved() == true){
                $email = (new Email())
                ->from('admin@exemple.com')
                ->to($emailRecru)
                ->subject('Vous avez un nouveau candidat postulé pour votre annonce')
                ->text(
                  "Bonjour, \n\n".
                  "Vous avez un nouveau candidat postulé pour votre annonce.\n\n".
                  "Candidat: ".$applyCandidateFirstname." ".$applyCandidateLastname."\n\n".
                  "Cv: ".$applyCandidateCv."\n\n".
                  
                  "Merci pour votre confiance.\n\n".
                  "Cordialement,\n\n".
                  "RecruSite.com"
                );

                $mailer->send($email);
              }
                }
               
        return $this->render('apply/index.html.twig', [
            'applies' => $applies,
            // 'allApplies' => $allApplies,
            // 'appliesAuthor' => $appliesAuthor
        ]);          
        
        }
  
    }
    

