<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Alert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AccountSettingsController extends AbstractController
{
    #[Route('/account/settings', name: 'app_account_settings')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        // TODO degager les notifs d'ici
        $alerted_deals = $entityManager->getRepository(Alert::class)->findAlertedDealsByUser($this->getUser()->getId());

        return $this->render('account/settings.html.twig', [
            'alerted_deals' => $alerted_deals,
        ]);
    }

    #[Route('/account/settings/update/pseudo', name: 'app_account_settings_update_pseudo')]
    public function updatePseudo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user !== null) {
            $newPseudo = $request->request->get('pseudo');
            $user->setPseudo($newPseudo);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        // Rediriger vers la page des paramètres
        return $this->redirectToRoute('app_account_settings');
    }

    #[Route('/account/settings/update/email', name: 'app_account_settings_update_email')]
    public function updateEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user !== null) {
            $newEmail = $request->request->get('email');
            $user->setEmail($newEmail);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        // Rediriger vers la page des paramètres
        return $this->redirectToRoute('app_account_settings');
    }

    #[Route('/account/settings/update/password', name: 'app_account_settings_update_password')]
    public function updatePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        
        if ($user !== null) {
            $newPassword = $request->request->get('password');
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $newPassword
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_account_settings');
    }

    public function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-+=';
        $randomString = '';

        $characterCount = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $characterCount - 1)];
        }

        return $randomString;
    }

    #[Route('/account/settings/delete', name: 'app_account_settings_delete')]
    public function delete(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();

        if ($user !== null) {
            $randomString = $this->generateRandomString(4);

            $pseudo = 'Anonyme_' . $randomString;
            $user->setPseudo($pseudo);

            
            $email = 'anonyme_' . $randomString . '@example.com';
            $user->setEmail($email);

            $password = $this->generateRandomString(12);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_logout');
    }




}
