<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\DealRepository;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DealController extends AbstractController
{
    #[Route('/deal/{id}', name: 'app_deal')]
    public function index(DealRepository $dealRepository, int $id, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $deal = $dealRepository->find($id);
        $comments = $commentRepository->findCommentsByDeal($id);
        $hotestDeals = $dealRepository->findHottestDeals();

        if (!$deal) {
            throw $this->createNotFoundException('Deal not found');
        }

        // Création du formulaire de commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'You must be logged in to post a comment');
                return $this->redirectToRoute('app_login');
            }
            
            // Définir les valeurs du commentaire
            $comment->setUser($this->getUser());
            $comment->setDeal($deal);
            $comment->setContent($form->get('content')->getData());

            $entityManager->persist($comment);
            $entityManager->flush();

            // Rediriger ou effectuer d'autres actions après l'enregistrement du commentaire
            return $this->redirectToRoute('app_deal', ['id' => $deal->getId()]);
        }

        return $this->render('deal/deal.html.twig', [
            'deal' => $deal,
            'comments' => $comments,
            'hotestDeals' => $hotestDeals,
            'commentForm' => $form->createView(),
        ]);
    }
}
