<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DealRepository $dealRepository): Response
    {
        $deals = $dealRepository->findFeaturedDeals();
        $hotestDeals = $dealRepository->findHottestDeals();

        return $this->render('home/index.html.twig', [
            'deals' => $deals,
            'hotestDeals' => $hotestDeals,
        ]);
    }

    #[Route('/search', name: 'app_search', methods: ['POST'])]
    public function search(Request $request, DealRepository $dealRepository): Response
    {
        $searchQuery = $request->request->get('search_query');

        $deals = $dealRepository->searchedDeals($searchQuery);

        $hotestDeals = $dealRepository->findHottestDeals();

        return $this->render('home/index.html.twig', [
            'deals' => $deals,
            'hotestDeals' => $hotestDeals,
        ]);
    }
}
