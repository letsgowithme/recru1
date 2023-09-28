<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\Column(length: 50)]
#[Assert\NotBlank()]
#[Assert\Length(min: 2, max: 50)]
private ?string $lastname = null;

#[ORM\Column(length: 180)]
#[Assert\Email()]
#[Assert\Length(min: 2, max: 50)]
private ?string $email = null;

#[ORM\Column(length: 100)]
#[Assert\Length(min: 2, max: 100)]
private ?string $subject = null;

#[ORM\Column(type: Types::TEXT)]
#[Assert\NotBlank()]
private ?string $message = null;

#[ORM\Column]
#[Assert\NotBlank()]
private ?\DateTimeImmutable $createdAt = null;

public function __construct()
{
$this->createdAt = new \DateTimeImmutable();
}

public function getId(): ?int
{
return $this->id;
}

/**
* Get the value of lastname
*/ 
public function getLastname()
{
return $this->lastname;
}

/**
* Set the value of lastname
*
* @return self
*/ 
public function setLastname($lastname)
{
$this->lastname = $lastname;

return $this;
}

public function getEmail(): ?string
{
return $this->email;
}

public function setEmail(string $email): self
{
$this->email = $email;

return $this;
}

public function getSubject(): ?string
{
return $this->subject;
}

public function setSubject(string $subject): self
{
$this->subject = $subject;

return $this;
}

public function getMessage(): ?string
{
return $this->message;
}

public function setMessage(string $message): self
{
$this->message = $message;

return $this;
}

public function getcreatedAt(): ?\DateTimeImmutable
{
return $this->createdAt;
}

public function setcreatedAt(\DateTimeImmutable $createdAt): self
{
$this->createdAt = $createdAt;

return $this;
}
public function __toString()
{
    return $this->lastname;
    
}
}

