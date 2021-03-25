<?php

namespace App\Controller\Back;

use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Repository\DeliveryPointRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="back_main")
     */
    public function index(DeliveryPointRepository $deliveryPointRepository): Response
    {
        $deliveryPoints = $deliveryPointRepository->findAll();

        $markers = [];
        foreach ($deliveryPoints as $deliveryPoint) {
            $markers[$deliveryPoint->getLocation()] = count($deliveryPoint->getOrders());
        }

        return $this->render('back/main/index.html.twig', [
            'controller_name' => 'MainController',
            'markers' => $markers,
        ]);
    }

    public function comfirmMail($token): Response
    {
        return $this->render('back/confirm.html.twig');
    }

    /**
     * Confirm a user after he received a confirmation mail containing a token
     * 
     * @Route("/confirm/{token}", name="back_confirm")
     */
    public function confirm($token, UserRepository $userRepository): Response
    {
        // Find if a user matches with the token
        $user = $userRepository->findByToken($token);

        // If no user has this token, then it's an error
        if (null === $user) {
            dd("Aucun utilisateur ne correspond a ce token.");
        }

        // Change the status to true (= active)
        $user->setStatus(true);

        dd($user->getUsername() . " a verifié son compte");
        // TODO redirect to the front
    }
}
