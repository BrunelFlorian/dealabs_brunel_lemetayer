<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetWeeklyDealsController
{
    private $dealRepository;

    public function __construct(DealRepository $dealRepository)
    {
        $this->dealRepository = $dealRepository;
    }

    public function __invoke(): JsonResponse
    {
        // Récupérer les deals de la semaine depuis le repository
        $weeklyDeals = $this->dealRepository->findWeeklyDeals();

        // Transformer les données des deals en tableau
        $dealsData = [];
        foreach ($weeklyDeals as $deal) {
            $dealsData[] = $deal;
        }

        // Retourner les deals sous forme de réponse JSON
        return new JsonResponse($dealsData);
    }
}
