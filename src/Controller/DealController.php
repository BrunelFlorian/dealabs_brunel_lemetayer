<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DealController extends AbstractController
{
    #[Route('/deal/{id}', name: 'app_deal')]
    public function index(int $id, DealRepository $dealRepository): Response
    {
        $deal = $dealRepository->find($id);
        $hotestDeals = $dealRepository->findHottestDeals();

        if (!$deal) {
            throw $this->createNotFoundException('Deal not found');
        }

        return $this->render('deal/deal.html.twig', [
            'deal' => $deal,
            'hotestDeals' => $hotestDeals,
        ]);
    }
}
