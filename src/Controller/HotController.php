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
        $deals = $dealRepository->findAll();

        return $this->render('hot/index.html.twig', [
            'controller_name' => 'HotController',
            'deals' => $deals,
        ]);
    }
}