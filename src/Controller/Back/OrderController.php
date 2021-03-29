<?php

namespace App\Controller\Back;

use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Component\Mime\Email;
use App\Repository\OrderRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('back/order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('back/order/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     * @Entity("order", expr="repository.findOne(id)")
     */
    public function show(Order $order): Response
    {
        return $this->render('back/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $order, Mailer $mailer): Response
    {
        $oldstatus = $order->getStatus();

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $user = $order->getUser();

            $status = [
                'En attente de préparation',
                'En préparation',
                'En livraison'
            ];

            // if the status is not the same as $oldStatus, it means status has been modified, we need to send a mail
            if ($order->getStatus() !== $oldstatus && $order->getStatus() < 3) {
                $email = (new Email())
                    ->from(new Address('onthespot@apotheoz.tech', 'OnTheSpot'))
                    ->to($user->getEmail())
                    ->subject('Votre commande #' . $order->getId() . ' est maintenant ' . $status[$order->getStatus()])
                    ->text('Bonjour ' . $user->getFirstname() . '\nNous avons le plaisir de vous annoncer que votre commande est maintenant ' . $order->getId() . '.')
                    ->html('<p>Bonjour ' . $user->getFirstname() . '</p><p>Nous avons le plaisir de vous annoncer que votre commande est maintenant ' . $order->getId() . '.');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                }
            }

            return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
        }

        return $this->render('back/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index');
    }
}
