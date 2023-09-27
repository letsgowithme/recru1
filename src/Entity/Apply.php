<?php

namespace App\Entity;

use App\Repository\ApplyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(
    fields: ['candidate', 'job'],
    errorPath: 'candidate',
    message: 'Vous avez déjà postulé pour cette annonce !'
)]
// #[UniqueEntity(fields: ['candidate'], message: 'Vous avez déjà postuler pour cette annonce')]
#[ORM\Entity(repositoryClass: ApplyRepository::class)]
class Apply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isApproved = false;


    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

  
    #[ORM\ManyToOne(inversedBy: 'applies')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?User $candidate = null;

    #[ORM\ManyToOne(inversedBy: 'applies')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Job $job = null;

    // #[ORM\OneToMany(mappedBy: 'apply',targetEntity: Notifications::class)]
    // private Collection $notifications;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        // $this->notifications = new ArrayCollection();
       
    }


    public function getId(): ?int
    {
        return $this->id;
    }

/**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }



    /**
     * Get the value of isApproved
     */
    public function getisApproved()
    {
        return $this->isApproved;
    }

    /**
     * Set the value of isApproved
     *
     * @return  self
     */
    public function setisApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }


    /**
     * Get the value of createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }



    /**
     * Get the value of candidate
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Set the value of candidate
     *
     * @return  self
     */
    public function setCandidate($candidate)
    {
        // if ($candidate === null && $this->candidate !== null) {
        //     $this->candidate->setJobs(null);
        // }

        // set the owning side of the relation if necessary
        // if ($candidate !== null && $candidate->getJobs() !== $this) {
        //     $candidate->setJobs($this);
        // }
        $this->candidate = $candidate;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

//   /**
//      * @return Collection<int, Notification>
//      */
//     public function getNotifications(): Collection
//     {
//         return $this->notifications;
//     }

//     public function addNotification(Notification $notification): self
//     {
//         if (!$this->notifications->contains($notification)) {
//             $this->notifications->add($notification);
//             $notification->setApply($this);
//         }

//         return $this;
//     }

//     public function removeNotification(Notification $notification): self
//     {
//         if ($this->notifications->removeElement($notification)) {
//             // set the owning side to null (unless already changed)
//             if ($notification->getApply() === $this) {
//                 $notification->setApply(null);
//             }
//         }

//         return $this;
//     }
}