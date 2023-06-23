<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AlertService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function processUserAlerts(User $user)
    {
        $alerts = $user->getAlerts();

        foreach ($alerts as $alert) {
            if ($alert->isEmailNotificationEnabled()) {
                $email = (new Email())
                    ->from('sender@example.com')
                    ->to($user->getEmail())
                    ->subject('Nouvelle alerte')
                    ->text('Une nouvelle alerte correspondant à vos critères a été trouvée.')
                    ->html('<p>Une nouvelle alerte correspondant à vos critères a été trouvée.</p>');

                $this->mailer->send($email);
            }
        }
    }
}
