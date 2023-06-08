<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deal = new Deal();
        $form = $this->createForm(PostFormType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire ici
            $deal->setTitle($form->get('title')->getData());
            $deal->setPrice($form->get('price')->getData());
            $deal->setDescription($form->get('description')->getData());
            $deal->setExpirationDate($form->get('expirationDate')->getData());
            $deal->setUserCreated($this->getUser()->getId());
            $deal->setCreatedAt(new \DateTimeImmutable());
            $deal->setCategory($form->get('category')->getData());
            $deal->setNotation(0);
            
            $entityManager->persist($deal);
            $entityManager->flush();
            // Redirigez vers une autre page après le traitement
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
