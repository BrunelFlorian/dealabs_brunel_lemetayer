<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\Rating;
use App\Entity\SavedDeal;
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
        $percentage_hot_deals = $entityManager->getRepository(Deal::class)->percentageHotPostedDealByUser($user_id);
        $rated_deals = $entityManager->getRepository(Rating::class)->countDealRatedByUser($user_id);
        $number_alerts = $entityManager->getRepository(Alert::class)->findNumberOfAlertsByUser($this->getUser()->getId());
        $number_saved_deals = $entityManager->getRepository(SavedDeal::class)->findNumberOfSavedDealsByUser($this->getUser()->getId());

        // TODO degager les notifs d'ici
        $alerted_deals = $entityManager->getRepository(Alert::class)->findAlertedDealsByUser($this->getUser()->getId());

        return $this->render('account/preview.html.twig', [
            'posted_deals' => $posted_deals,
            'posted_comments' => $posted_comments,
            'hottest_rating' => $hottest_rating,
            'average_rating' => $average_rating,
            'percentage_hot_deals' => $percentage_hot_deals,
            'rated_deals' => $rated_deals,
            'number_alerts' => $number_alerts,
            'number_saved_deals' => $number_saved_deals,
            'alerted_deals' => $alerted_deals,
        ]);
    }
}
