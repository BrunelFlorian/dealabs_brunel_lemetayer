<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Repository\NotificationRepository;

class LoginListener
{
    private $session;
    private $tokenStorage;
    private $notificationRepository;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        NotificationRepository $notificationRepository
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->notificationRepository = $notificationRepository;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $notifications = $this->notificationRepository->findNotificationsByUser($user->getId());

        $this->session->set('notifications', $notifications);
    }
}
