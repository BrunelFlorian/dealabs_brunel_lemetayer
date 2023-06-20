<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Deal;
use App\Form\AlertFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAlertController extends AbstractController
{
    #[Route('/account/alert', name: 'app_account_alert')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $alert = new Alert();
        $form = $this->createForm(AlertFormType::class, $alert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $alert->setUser($user);

            $entityManager->persist($alert);
            $entityManager->flush();

            $this->addFlash('success', 'Your alert has been created.');
        }

        $posted_deals = $entityManager->getRepository(Deal::class)->findNumberOfDealsByUser($this->getUser()->getId());

        return $this->render('account/alert.html.twig', [
            'alertForm' => $form->createView(),
            'posted_deals' => $posted_deals,
        ]);
    }
}
