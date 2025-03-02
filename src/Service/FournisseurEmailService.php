<?php
namespace App\Service;

use App\Entity\Fournisseur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FournisseurEmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(Fournisseur $fournisseur, string $subject, string $body): void
    {
        if (!$fournisseur->getEmail()) {
            throw new \InvalidArgumentException('Fournisseur email is missing.');
        }

        $email = (new Email())
            ->from('your_email@example.com') // Replace with a configured sender email
            ->to($fournisseur->getEmail())
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
