<?php

namespace App\Controller;

use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountSaveDealController extends AbstractController
{
    #[Route('/account/saveDeal', name: 'app_account_saveDeal')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());
        $number_alerts = $entityManager->getRepository(Alert::class)->findNumberOfAlertsByUser($this->getUser()->getId());
        return $this->render('account/save_deal.html.twig', [
            'posted_deals' => $posted_deals,
            'number_alerts' => $number_alerts,
        ]);
    }
}
