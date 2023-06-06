<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Repository\DealRepository;
use App\Repository\RatingRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    #[Route('/ajax/deal/{id}/update', name: 'ajax_deal_update', methods: ['POST'])]
    public function dealUpdate(Request $request, DealRepository $dealRepository, RatingRepository $ratingRepository, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();
        $deal = $dealRepository->find($id);
        $rating = $ratingRepository->findOneBy([
            'user' => $user,
            'deal' => $deal,
        ]);

        if ($rating) {
            return new JsonResponse(['error' => 'You have already voted for this deal.'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            $rating = new Rating();
            $rating->setUser($user);
            $rating->setDeal($deal);
        }

        if (!$deal) {
            return new JsonResponse(['error' => 'Deal not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($request->request->get('type') == "increase") {
            $deal->setNotation($deal->getNotation() + 1);
        } else {
            $deal->setNotation($deal->getNotation() - 1);
        }
        
        $entityManager->persist($deal);
        $entityManager->persist($rating);
        $entityManager->flush();

        $data = [
            'notation' => (int)$deal->getNotation(),
            'dealId' => (int)$id,
        ];

        return new JsonResponse(['success' => $data]);
    }
}
