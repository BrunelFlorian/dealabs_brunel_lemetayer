<?php

namespace App\Controller;

use App\Repository\SavedDealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetSavedDealsByUserController extends AbstractController 
{
    private $savedDealRepository;

    public function __construct(SavedDealRepository $savedDealRepository)
    {
        $this->savedDealRepository = $savedDealRepository;
    }

    public function __invoke(): array
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \LogicException('Cannot get saved deals for an anonymous user.');
        }

        // Récupérer les deals de la semaine depuis le repository
        $savedDeals = $this->savedDealRepository->findSavedDealsByUser($user->getId());

        return $savedDeals;
    }
}
