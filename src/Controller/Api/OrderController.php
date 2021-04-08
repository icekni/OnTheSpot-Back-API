<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * Browse the orders for the connected user
     * 
     * @Route("/api/orders", name="api_order_browse", methods={"GET"})
     */
    public function browse(Security $security, OrderRepository $orderRepository): Response
    {
        $user = $security->getUser();

        $orders = $orderRepository->findBy([
            'user' => $user,
        ]);

        return $this->json($orders, 200, [], ['groups' => [
            'api_order_browse',
            'api_order_read_one'
        ]]);
    }


    /**
     * Read one order
     *
     * @Route("/api/orders/{id<\d+>}", name="api_order_read_one", methods={"GET"})
     */
    public function read(Security $security, Order $order = null): Response
    {
        // We send a custom message if order not found (404)
        if ($order === null) {

            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Commande non trouvée.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        // We get the connected user's id
        $user = $security->getUser();
        $userId = $user->getId();

        // We get the the user's id of the order
        $orderUserId = $order->getUser()->getId();

        // If the requested order is not the one of the connected user, he won't be able to see it
        if ($userId === $orderUserId) {
            return $this->json($order, 200, [], ['groups' => [
                'api_order_read_one',
            ]]);
        } else {
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Ce n\'est pas votre commande !',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * Post a new order
     * 
     * @Route("/api/orders", name="api_order_create", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, Security $security, ValidatorInterface $validator, MailerInterface $mailer)
    {
        // Getting the JSON content of the request
        $jsonContent = $request->getContent();

        // Transforming the JSON in Order entity with the serializer
        $order = $serializer->deserialize(
            $jsonContent,
            Order::class,
            'json'
        );
        // We get the connected user's id
        $connectedUser = $security->getUser();

        // We set order's user as the connected 
        $order->setUser($connectedUser);

        // Validation        
        $errors = $validator->validate($order);

        // In case of error
        if (count($errors) > 0) {
            // Send a json containing all errors
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Saving the order
            $entityManager->persist($order);
            // Creating the order in the database
            $entityManager->flush();
        } catch (NotNullConstraintViolationException $e) {
            return $this->json($e->getMessage());
        }

        $email = (new TemplatedEmail())
            ->from(new Address('onthespot@apotheoz.tech', 'OnTheSpot'))
            ->to($order->getUser()->getEmail())
            ->subject('Votre commande a été recue!')

            // path of the Twig template to render
            ->htmlTemplate('email/confirm-order.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'order' => $order
            ]);

        $mailer->send($email);

        return $this->json($order, Response::HTTP_CREATED, [], ['groups' => [
            'api_order_read_one',
        ]]);
    }
}
