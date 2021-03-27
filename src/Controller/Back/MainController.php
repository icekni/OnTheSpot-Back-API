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

        // Change the status to true (= active)
        $user->setStatus(true);

        dd($user->getUsername() . " a verifié son compte");
        // TODO redirect to the front
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        $result = file_get_contents('https://api-adresse.data.gouv.fr/reverse/?lon=-1.4413976669311523&lat=43.67876671584565');
        dd(json_decode($result)->features[0]->properties->city);
    }
}
