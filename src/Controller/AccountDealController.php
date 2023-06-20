<?php

namespace App\Controller;

use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountDealController extends AbstractController
{
    #[Route('/account/deal', name: 'app_account_deal')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());
        return $this->render('account/account_deal.html.twig', [
            'posted_deals' => $posted_deals,
        ]);
    }
}
