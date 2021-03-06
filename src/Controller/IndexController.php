<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    #[Route('/{route<^(?!build|api|admin.*$).*>}', name: 'app_vue_pages', methods: ['GET'], priority: -1)]
    public function index(): Response
    {
        return $this->render('vue_base.html.twig');
    }
}
