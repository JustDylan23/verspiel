<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route(path: '/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
    }
}
