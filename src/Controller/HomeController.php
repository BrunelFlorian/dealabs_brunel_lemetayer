<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DealRepository $dealRepository): Response
    {
        $deals = $dealRepository->findAll();
        $featuredDeals = $dealRepository->findFeaturedDeals();
        $hotestDeals = $dealRepository->findHottestDeals();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'featuredDeals' => $featuredDeals,
            'deals' => $deals,
            'hotestDeals' => $hotestDeals,
        ]);
    }
}
