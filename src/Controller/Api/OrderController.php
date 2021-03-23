<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class OrderController extends AbstractController
{
    /**
     * Browse the orders for the connected user
     * 
     * @Route("/api/orders", name="api_order_browse", methods={"GET"})
     */
    public function browse(Security $security, OrderRepository $orderRepository): Response
    {
        $customer = $security->getUser();

        $orders = $orderRepository->findBy([
            'customer' => $customer,
        ]);

        return $this->json($orders, 200, [], ['groups' => [
            'api_order_browse',
        ]]);
    }


    /**
     * Read one order
     *
     * @Route("/api/orders/{id<\d+>}", name="api_order_read_one", methods={"GET"})
     */
    public function read(Order $order = null): Response
    {
        // We send a custom message if order not found (404)
        if ($order === null) {

            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Commande non trouvée.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($order, 200, [], ['groups' => [
            'api_order_read_one',
        ]]);
    }


    /**
     * Post a new order
     * 
     * @Route("/api/orders", name="api_order_create", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        // Getting the JSON content of the request
        $jsonContent = $request->getContent();


        // Transforming the JSON in Order entity with the serializer
        $order = $serializer->deserialize(
            $jsonContent,
            Order::class,
            'json'
        );

        // TODO validation

        // Saving the order
        $entityManager->persist($order);

        // Creating the order in the database
        $entityManager->flush();

        // After the creation, we redirect to the route "api_order_read_one" of the created order
        return $this->redirectToRoute(
            'api_order_read_one',
            ['id' => $order->getId()],
            Response::HTTP_CREATED
        );
    }
}
