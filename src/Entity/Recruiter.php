<?php

namespace App\Entity;

use App\Repository\RecruiterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruiterRepository::class)]
class Recruiter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'recruiter', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
  
     #[ORM\OneToMany(mappedBy: 'recruiter', targetEntity: Job::class)]
    private Collection $jobs;

      public function __construct()
    {
      $this->jobs = new ArrayCollection();
       
       
    }
    public function getId(): ?int
    {
        return $this->id;
    }

  /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
   

    /**
     * Get the value of jobs
     */ 
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Set the value of jobs
     *
     * @return  self
     */ 
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;

        return $this;
    }
}