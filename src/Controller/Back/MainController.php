<?php

namespace App\Controller\Back;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
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

    public function comfirmMail($token): Response
    {
        return $this->render('back/confirm.html.twig');
    }

    /**
     * @Route("/test", name="back_test")
     */
    public function mail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from(new Address('onthespot@apotheoz.tech', 'OnTheSpot'))
            ->to('cjosso@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text($random = bin2hex(random_bytes(10)))
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Nothing
        }

        return $this->redirectToRoute('back_main');
    }
}
