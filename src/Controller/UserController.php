<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user')]
class UserController extends AbstractController
{   
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'user.index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'user.new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('user.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user.show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        EntityManagerInterface $manager,
        User $user, 
        UserRepository $userRepository,
        SluggerInterface $slugger
        ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cvFilename')->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
            $user->setCvFilename($newFilename);

            $manager->persist($user);
            $manager->flush();
            }
            $this->addFlash(
                'success',
                'Votre profile a été modifié avec succès !'
            );
            return $this->redirectToRoute('job.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

   
    #[Route('/delete/{id}', 'user.delete', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function delete(
        EntityManagerInterface $manager,
        User $user
    ): Response {
       
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre profile a été supprimé avec succès !'
        );

        return $this->redirectToRoute('user.index');
    }
}
