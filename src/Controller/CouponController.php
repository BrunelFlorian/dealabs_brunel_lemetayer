<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CouponController extends AbstractController
{
    #[Route('/coupon', name: 'app_coupon')]
    public function index(DealRepository $dealRepository): Response
    {
        $coupons = $dealRepository->findAll();

        return $this->render('coupon/coupon.html.twig', [
            'coupons' => $coupons,
        ]);
    }
}
