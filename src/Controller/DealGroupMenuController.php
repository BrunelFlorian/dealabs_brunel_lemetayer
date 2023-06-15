<?php

namespace App\Controller;

use App\Repository\DealGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DealGroupMenuController extends AbstractController
{
    public function showDealGroupMenu(DealGroupRepository $dealGroupRepository): Response
    {
        $dealGroups = $dealGroupRepository->findAll();

        return $this->render('deal_group_menu/_menu.html.twig', [
            'dealGroups' => $dealGroups,
        ]);
    }
}
