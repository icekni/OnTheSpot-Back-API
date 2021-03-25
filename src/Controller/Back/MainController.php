<?php

namespace App\Controller\Back;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="back_main")
     */
    public function index(): Response
    {
        return $this->render('back/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/test", name="back_test")
     */
    public function mail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('onthespot@apotheoz.tech')
            ->to('cjosso@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dump($e);
        }

        return $this->redirectToRoute('back_main');
    }
}
