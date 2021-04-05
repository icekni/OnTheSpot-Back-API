<?php

namespace App\Controller\Back;

use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Mime\Address;
use App\Repository\DeliveryPointRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/{id<\d+>}", name="back_main")
     */
    public function index($id = null, DeliveryPointRepository $deliveryPointRepository, OrderRepository $orderRepository): Response
    {
        $ordersCount = $orderRepository->countAllOrderOnDeliveryPoint();

        // If there is an id in the url, we will display all orders for this DeliveryPoint
        $deliveryPoint = null;
        if ($id) {
            $deliveryPoint = $deliveryPointRepository->find($id);

            return $this->render('back/main/index.html.twig', [
                'markers' => $ordersCount,
                'deliveryPoint' => $deliveryPoint,
                'orders' => $orderRepository->findAllActive($deliveryPoint),
            ]);
        }

        return $this->render('back/main/index.html.twig', [
            'markers' => $ordersCount,
            'orders' => $orderRepository->findAllActive(),
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

        // Change the status to 1 (= active)
        $user->setStatus(1);

        return $this->render('back/main/confirm.html.twig');
    }
}
