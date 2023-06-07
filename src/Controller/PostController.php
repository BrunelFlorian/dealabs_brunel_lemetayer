<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Form\DealType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(Request $request): Response
    {
        $deal = new Deal();
        $form = $this->createForm(DealType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire ici

            // Redirigez vers une autre page après le traitement
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
