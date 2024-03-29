<?php

namespace App\DataFixtures;

use App\Entity\Apply;
use App\Entity\Candidate;
use App\Entity\Comment;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Job;
use App\Entity\Recruiter;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;


    public function  __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        // Users
        // $users = [];

        // $super_admin = new User();
        // $super_admin->setLastname('Administrateur')
        //        ->setEmail('admin@recru.fr')
        //        ->setRoles(['ROLE_SUPER_ADMIN'])
        //        ->setPlainPassword('password')
        //        ->setIsVerified(true)
        //        ;
        //        $users[] = $super_admin;
        //    $manager->persist($super_admin);
           
           $admin = new User();
           $admin->setLastname('Consultant')
                  ->setEmail('admin@admin.fr')
                  ->setRoles(['ROLE_ADMIN'])
                  ->setPlainPassword('password')
                  ->setIsVerified(true)
                  ;
               
                  $users[] = $admin;
              $manager->persist($admin);

                    
        for ($j = 0; $j < 10; $j++) {
           $user = new User();
           $user->setEmail($this->faker->email())    
           ->setPlainPassword('password')
           ->setRoles(mt_rand(0, 1) == 1 ? ['ROLE_CANDIDATE'] : ['ROLE_RECRUITER']);
           if($user->getRoles() == 'ROLE_CANDIDATE'){
            $user->setLastname($this->faker->lastname())
                 ->setFirstname($this->faker->firstname());
           }else{
            $user ->setCompany($this->faker->word())
            ->setLocation($this->faker->address());
           }
           
               $users[] = $user;
           $manager->persist($user);
       }

    //    aplly
    for ($j = 0; $j < 10; $j++) {
        $apply = new Apply();
        $apply->setContent($this->faker->text(30))    
              ->setIsApproved(mt_rand(0, 1) == 1 ? true : false);
              for ($k = 0; $k < mt_rand(5, 5); $k++) {
        if( $user->getRoles() == 'ROLE_CANDIDATE'){
            $candidate = $user;
            $apply->setCandidate($users[mt_rand(0, count($users) - 1)]); 
            }
        }
        
            $applies[] = $apply;
        $manager->persist($apply);
    }
    
         //Jobs
         $jobs = [];
         for ($i = 0; $i < 10; $i++) {
             $job = new Job();
             $job->setTitle($this->faker->word())
                 ->setCompany($this->faker->word())
                 ->setLocation($this->faker->address())
                 ->setCity($this->faker->city())
                 ->setDescription($this->faker->text(100))
                 ->setSalary(mt_rand(1500, 5000))
                 ->setSchedule($this->faker->text(100))
                 ->setIsApproved(mt_rand(0, 1) == 1 ? true : false);
                 for ($k = 0; $k < mt_rand(5, 5); $k++) {
                    if( $user->getRoles() == 'ROLE_RECRUITER'){
                        $recruiter = $user;
                    $job->setAuthor($users[mt_rand(0, count($users) - 1)]);
                }
            }
                 for ($k = 0; $k < mt_rand(5, 5); $k++) {
                    $job->addApply($applies[mt_rand(0, count($applies) - 1)]); 
                }
               
             $jobs[] = $job;
             $manager->persist($job);
         }    
     

        $manager->flush();
    }
}
