<?php

namespace App\Controller;

use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAlertController extends AbstractController
{
    #[Route('/account/alert', name: 'app_account_alert')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());
        return $this->render('account/alert.html.twig', [
            'posted_deals' => $posted_deals,
        ]);
    }
}
