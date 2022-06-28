<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationCompleteController extends AbstractController
{
    #[Route('/registration/complete/{token}', name: 'register_complete', methods: ['GET'])]
    public function registerComplete(string $token, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['emailVerificationToken' => $token]);

        if (null === $user) {
            return $this->redirectToRoute('app_vue_pages', ['route' => 'not-found']);
        }

        $user->setVerified(true);

        $entityManager->flush();

        return $this->redirectToRoute('app_vue_pages', ['route' => 'registration/completed']);
    }
}
