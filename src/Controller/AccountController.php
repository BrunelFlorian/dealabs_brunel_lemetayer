<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user_id = $this->getUser()->getId();

        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($user_id);

        $posted_comments = $entityManager->getRepository(Comment::class)->findNumberOfCommentsByUser($user_id);

        $hottest_rating = $entityManager->getRepository(Deal::class)->findRateHottestDealByUser($user_id);

        $average_rating = $entityManager->getRepository(Deal::class)->findAverageRateDealsByUser($user_id);

        return $this->render('account/account.html.twig', [
            'posted_deals' => $posted_deals,
            'posted_comments' => $posted_comments,
            'hottest_rating' => $hottest_rating,
            'average_rating' => $average_rating,
        ]);
    }
}
