<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetWeeklyDealsController extends AbstractController 
{
    private $dealRepository;

    public function __construct(DealRepository $dealRepository)
    {
        $this->dealRepository = $dealRepository;
    }

    public function __invoke(): array
    {
        // Récupérer les deals de la semaine depuis le repository
        $weeklyDeals = $this->dealRepository->findWeeklyDeals();

        return $weeklyDeals;
    }
}
