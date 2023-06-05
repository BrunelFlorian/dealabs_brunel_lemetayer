<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(DealRepository $dealRepository): Response
    {
        $deals = $dealRepository->findAll();
        $featuredDeals = $dealRepository->findFeaturedDeals();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'featuredDeals' => $featuredDeals,
            'deals' => $deals,
        ]);
    }
}
