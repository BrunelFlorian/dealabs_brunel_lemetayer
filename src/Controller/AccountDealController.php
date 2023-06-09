<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Deal;
use App\Entity\SavedDeal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountDealController extends AbstractController
{
    #[Route('/account/deal', name: 'app_account_deal')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $number_posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());
        $posted_deals = $entityManager->getRepository(Deal::class)->findDealsByUser($this->getUser()->getId());
        $number_alerts = $entityManager->getRepository(Alert::class)->findNumberOfAlertsByUser($this->getUser()->getId());
        $number_saved_deals = $entityManager->getRepository(SavedDeal::class)->findNumberOfSavedDealsByUser($this->getUser()->getId());

        // TODO degager les notifs d'ici
        $alerted_deals = $entityManager->getRepository(Alert::class)->findAlertedDealsByUser($this->getUser()->getId());

        return $this->render('account/account_deal.html.twig', [
            'number_posted_deals' => $number_posted_deals,
            'posted_deals' => $posted_deals,
            'number_alerts' => $number_alerts,
            'number_saved_deals' => $number_saved_deals,
            'alerted_deals' => $alerted_deals,
        ]);
    }
}
