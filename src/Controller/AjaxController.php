<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function PHPSTORM_META\type;

class AjaxController extends AbstractController
{
    #[Route('/ajax/deal/{id}/update', name: 'ajax_deal_update', methods: ['POST'])]
    public function dealUpdate(Request $request, DealRepository $dealRepository, $id): JsonResponse
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

        $entityManager = $dealRepository->getEntityManager();
        $entityManager->persist($deal);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Deal updated successfully.']);
    }
}
