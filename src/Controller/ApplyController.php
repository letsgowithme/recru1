<?php

namespace App\Controller;

use App\Entity\Apply;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ApplyRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ApplyController extends AbstractController
{
    #[Route('/apply', name: 'app_apply', methods: ['GET'])]
    public function index(ApplyRepository $applyRepository,
    Request $request,
    EntityManagerInterface $manager,
    MailerInterface $mailer
    ): Response
    {
              $allApplies = $applyRepository->findAll();
              $applies = $applyRepository->findBy(['isApproved' => true]);
              $appliesAuthor = "";
              
              $emailSender = "admin@exemple.com";

            //   if($this->getParameter('isApproved' == true)){
            //   if($applies){
                 foreach ($applies as $apply) {
                   $emailRecru = $apply->getJob()->getAuthor()->getEmail();
                //    $applyCandidate = $apply->getJob()->getCandidate();
                //    $emailRecru = $apply->getJob()->getRecruiter()->getEmail();
                 //Email
                $email = (new Email())
                ->from($emailSender)
                ->to($emailRecru)
                ->subject('Votre demande d\'emploi a bien été prise en compte')
                // ->htmlTemplate('emails/email.html.twig')
                ;
                // ->context([
                //     'name' => $apply->getJob()->getTitle(),
                //     'email' => $apply->getJob()->getAuthor()->getEmail(),
                // ])
                
                $mailer->send($email)
                // $email->sendEmail(
                //     $from,
                //     $apply->getJob()->getAuthor()->getEmail(),
                //     'emails/apply.html.twig',
                //     ['apply' => $apply]
        ;

        
         $this->addFlash(
            'success',
            'Votre message a été envoyé avec succès !'
        );
                 }
            //   }
            
            
    
            return $this->render('apply/index.html.twig', [
                'applies' => $applies,
                'allApplies' => $allApplies,
            ]);
          
        
        }

  
    }
    

