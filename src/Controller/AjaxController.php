<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    #[Route('/ajax/deal/{id}/update', name: 'ajax_deal_update', methods: ['POST'])]
    public function dealUpdate(Request $request, DealRepository $dealRepository, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $deal = $dealRepository->find($id);
        if (!$deal) {
            return new JsonResponse(['error' => 'Deal not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($request->request->get('type') == "increase") {
            $deal->setNotation($deal->getNotation() + 1);
        } else {
            $deal->setNotation($deal->getNotation() - 1);
        }
        
        $entityManager->persist($deal);
        $entityManager->flush();

        $data = [
            'notation' => (int)$deal->getNotation(),
            'dealId' => (int)$id,
        ];

        return new JsonResponse(['success' => $data]);
    }
}
