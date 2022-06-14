<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    #[Route('/{route<^(?!build|api|admin.*$).*>}', name: 'vue_pages', priority: -1)]
    public function index(): Response
    {
        return $this->render('vue_base.html.twig');
    }

    #[Route('/api/test', methods: ['GET'])]
    public function test()
    {
        throw new \Error();
    }
}
