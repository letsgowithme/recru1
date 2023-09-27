<?php

namespace App\Controller;

use App\Entity\Apply;
use App\Form\ApplyType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ApplyRepository;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;


class ApplyController extends AbstractController
{
    #[Route('/apply', name: 'app_apply')]
    public function index(ApplyRepository $applyRepository,
    // $id,
    Request $request,
    Apply $apply,
    EntityManagerInterface $manager,
    MailService $mailService,
    // NotifierInterface $notifier,
    ): Response
    {
        $apply = new Apply();

        $applies = $applyRepository->findBy(['isApproved' => true]);
        if($this->getParameter('isApproved' == true)){
            $appliesAuthor =  $apply->getJob()->getAuthor();
            $emailRecru = $appliesAuthor->getEmail();
        }

        $form=$this->createForm(ApplyTypeType::class, $apply);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

             $apply = $form->getData();
             $manager->persist($apply);
             $manager->flush();

             //Email
             $mailService->sendEmail(
                $from,
                $apply->getJob()->getAuthor()->getEmail(),
                'emails/apply.html.twig',
                ['apply' => $apply]
            );

            
             $this->addFlash(
                'success',
                'Votre message a été envoyé avec succès !'
            );
    
            return $this->redirectToRoute('apply.index');
        
        }

       
        return $this->render('apply/index.html.twig', [
            'form' => $form->createView(),
            'applies' => $applies,
            'apply' => $apply

        ]);
    

    //     if($applies) {
    //       $notification = (new Notification('Nouveau cnadidat', ['email']))
    //       ->content('Vous avez un canddat postulé pour votre annonce');
    //        // The receiver of the Notification
    //   $recipient = new Recipient(
    //       $emailRecru
          
    //   );
    //   // Send the notification to the recipient
    //   $notifier->send($notification, $recipient);
    //   }
       
             
    //     return $this->render('apply/index.html.twig', [
    //         'applies' => $applies,
            
    //     ]);
    }
}
