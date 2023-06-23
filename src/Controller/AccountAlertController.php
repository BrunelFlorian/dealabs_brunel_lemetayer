<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Deal;
use App\Entity\Notification;
use App\Entity\SavedDeal;
use App\Form\AlertFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAlertController extends AbstractController
{
    #[Route('/account/alert', name: 'app_account_alert')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $alert = new Alert();
        $form = $this->createForm(AlertFormType::class, $alert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $alert->setUser($user);

            $entityManager->persist($alert);

            $this->addFlash('success', 'Your alert has been created.');
        }

        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());
        $alerts = $entityManager->getRepository(Alert::class)->findAlertsByUser($this->getUser()->getId());
        $number_alerts = $entityManager->getRepository(Alert::class)->findNumberOfAlertsByUser($this->getUser()->getId());
        $number_saved_deals = $entityManager->getRepository(SavedDeal::class)->findNumberOfSavedDealsByUser($this->getUser()->getId());
        
        $alerted_deals = $entityManager->getRepository(Alert::class)->findAlertedDealsByUser($this->getUser()->getId());
        $notifications = $entityManager->getRepository(Notification::class)->findNotificationsByUser($this->getUser()->getId());
        
        $deals_without_notifications = array_filter($alerted_deals, function ($deal) use ($notifications) {
            $dealId = $deal->getId();
            foreach ($notifications as $notification) {
                if ($notification->getDeal()->getId() === $dealId) {
                    return false;
                }
            }
                return true;
        });

        foreach ($deals_without_notifications as $deal) {
            $notification = new Notification();
            $notification->setDescription('New alert: new deal "' . $deal->getTitle() . '" posted.');
            $notification->setDeal($deal);
            $notification->setUser($this->getUser());

            $entityManager->persist($notification);
        }

        $entityManager->flush();

        return $this->render('account/alert.html.twig', [
            'posted_deals' => $posted_deals,
            'alertForm' => $form->createView(),
            'alerts' => $alerts,
            'number_alerts' => $number_alerts,
            'number_saved_deals' => $number_saved_deals,
            'alerted_deals' => $alerted_deals,
        ]);
    }

    #[Route('/alert/delete/{id}', name: 'app_delete_alert')]
    public function deleteAlert(int $id, EntityManagerInterface $entityManager): Response
    {
        $alert = $entityManager->getRepository(Alert::class)->find($id);
        $entityManager->remove($alert);
        $entityManager->flush();

        return $this->redirectToRoute('app_account_alert');
    }

    public function showNotifications(EntityManagerInterface $entityManager): Response
    {
        return $this->render('notifications/_notifications.html.twig');
    }

    #[Route('/notification/read/{id}/{dealId}', name: 'app_read_notification')]
    public function readNotification(int $id, int $dealId, EntityManagerInterface $entityManager): Response
    {
        $notification = $entityManager->getRepository(Notification::class)->find($id);
        $notification->setReaded(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_deal', ['id' => $dealId]);
    }
}
