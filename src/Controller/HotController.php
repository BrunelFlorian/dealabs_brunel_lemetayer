<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotController extends AbstractController
{
    #[Route('/hot', name: 'app_hot')]
    public function index(DealRepository $dealRepository): Response
    {
        $hotDeals = $dealRepository->findHotDeals();

        return $this->render('hot/hot.html.twig', [
            'controller_name' => 'HotController',
            'hotDeals' => $hotDeals,
        ]);
    }
}