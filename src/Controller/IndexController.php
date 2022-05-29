<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    #[Route('/{route<^(?!build|api|admin.*$).*>}', name: 'vue_pages', priority: -1)]
    public function index(): Response
    {
        return $this->render('vue_base.html.twig');
    }

//    #[Route('/test')]
//    public function test(MailerInterface $mailer): Response
//    {
//        $email = (new TemplatedEmail())
//            ->from('dylanvdhout@gmail.com')
//            ->to('dylanvdhout@gmail.com')
//            ->subject('Time for Symfony Mailer!')
//            ->htmlTemplate('emails/email_base.html.twig')
//        ;
//
//        $email
//            ->getHeaders()
//            ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply')
//            ;
//
//        $mailer->send($email);
//        return $this->render('base.html.twig');
//    }
}
