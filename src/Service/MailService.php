<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        string $from,
        string $subject,
        string $htmlTemplate,
        array $context,
        string $to = 'admin@exemple.com'
    ): void {
        $email = (new TemplatedEmail())
        ->from('recru_no_reply@recru.com')
        ->to('admin@exemple.com')
        ->subject('Info de candidat')
        ->htmlTemplate('<p>C\'est une information</p>');

        $this->mailer->send($email);
    }
}

