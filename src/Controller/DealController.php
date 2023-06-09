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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use App\Repository\SavedDealRepository;
use App\Entity\SavedDeal;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
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

        // Comments form
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'You must be logged in to post a comment');
                return $this->redirectToRoute('app_login');
            }

            $comment->setUser($this->getUser());
            $comment->setDeal($deal);
            $comment->setContent($form->get('content')->getData());
            $comment->setUserPseudo($this->getUser()->getUserPseudo());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_deal', ['id' => $deal->getId()]);
        }

        return $this->render('deal/deal.html.twig', [
            'deal' => $deal,
            'comments' => $comments,
            'hotestDeals' => $hotestDeals,
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/deal/{id}/increment-reports', name: 'app_deal_increment_reports')]
    public function incrementReports(int $id, DealRepository $dealRepository, Mailer $mailer, EntityManagerInterface $entityManager, Request $request): Response
    {
        $deal = $dealRepository->find($id);

        if (!$deal) {
            throw $this->createNotFoundException('Deal not found');
        }

        $deal->setReports($deal->getReports() + 1);
        $entityManager->flush();

        $adminEmail = 'admin@dealstrom.fr'; // Adresse e-mail de l'administrateur
        $subject = 'Deal Report';

        $reason = $request->request->get('reason');

        $email = (new TemplatedEmail())
            ->from(new Address('noreply@dealstorm.com', 'DealStorm Admin'))
            ->to($adminEmail)
            ->subject($subject)
            ->htmlTemplate('deal/deal_report.html.twig')
            ->context([
                'dealId' => $deal->getId(),
                'dealTitle' => $deal->getTitle(),
                'reportsCount' => $deal->getReports(),
                'reason' => $reason,
            ]);

        $mailer->send($email);

        return $this->redirectToRoute('app_deal', ['id' => $deal->getId()]);
    }

    #[Route('/deal/{id}/expired', name: 'app_deal_expired')]
    public function expiredDeal(int $id, DealRepository $dealRepository, EntityManagerInterface $entityManager): Response
    {
        $deal = $dealRepository->find($id);

        if (!$deal) {
            throw $this->createNotFoundException('Deal not found');
        }

        $deal->setIsExpired(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_deal', ['id' => $deal->getId()]);
    }

    #[Route('/deal/{id}/save', name: 'app_deal_save')]  //, methods: ['POST']
    public function saveDeal(int $id, Request $request, DealRepository $dealRepository, SavedDealRepository $savedDealRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $deal = $dealRepository->find($id);

        if (!$user || !$deal) {
            throw $this->createNotFoundException('User or Deal not found');
        }

        $savedDeal = new SavedDeal();
        $savedDeal->setUser($user);
        $savedDeal->setDeal($deal);
                
        $entityManager->persist($savedDeal);
        $entityManager->flush();

        return $this->redirectToRoute('app_deal', ['id' => $deal->getId()]);
    }
}
