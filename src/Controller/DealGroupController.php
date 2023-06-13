<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DealGroupController extends AbstractController
{
    #[Route('/dealgroup/{id}', name: 'app_deal_group')]
    public function index(DealRepository $dealRepository, int $id): Response
    {
        $dealsByGroup = $dealRepository->findDealsByGroup($id);
        return $this->render('deal_group/deal_group..html.twig', [
            'deals' => $dealsByGroup,
        ]);
    }
}
