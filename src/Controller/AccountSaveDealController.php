<?php

namespace App\Controller;

use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SavedDealRepository;
use App\Entity\Alert;

class AccountSaveDealController extends AbstractController
{
    #[Route('/account/saveDeal', name: 'app_account_saveDeal')]
    public function index(EntityManagerInterface $entityManager, SavedDealRepository $savedDealRepository): Response
    {
        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());
        $saved_deals = $savedDealRepository->findNumberOfSavedDealsByUser($this->getUser()->getId());
        // TODO degager les notifs d'ici
        // $alerted_deals = $entityManager->getRepository(Alert::class)->findAlertedDealsByUser($this->getUser()->getId());

        $savedDeals = $savedDealRepository->findSavedDealsByUser($this->getUser()->getId());

        return $this->render('account/save_deal.html.twig', [
            'posted_deals' => $posted_deals,
            'saved_deals' => $saved_deals,
            // 'alerted_deals' => $alerted_deals,
            'savedDeals' => $savedDeals,
        ]);
    }
}
