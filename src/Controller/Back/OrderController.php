<?php

namespace App\Controller\Back;

use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Component\Mime\Email;
use App\Repository\OrderRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/{id<\d+>}", name="order_show", methods={"GET"})
     * @Entity("order", expr="repository.findOne(id)")
     */
    public function show(Order $order): Response
    {
        return $this->render('back/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $order, MailerInterface $mailer): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We get the initial status and the new Status
            $currentStatus = $order->getStatus();
            $newStatus = $form->get('status')->getData();

            // If the status has changed
            if ($currentStatus !== $newStatus) {

                if ($newStatus === 1) {
                    $message = 'en préparation';
                } elseif ($newStatus === 2) {
                    $message = 'en livraison';
                } elseif ($newStatus === 3) {
                    $message = "arrivée à destination";
                }

                // Then we send an email no notify the user
                $email = (new Email())
                    ->from(new Address('onthespot@apotheoz.tech', 'OnTheSpot'))
                    ->to('pedr1ferre@gmail.com')
                    ->subject('Changement de statut de votre commande')
                    ->text('Re-bonjour ' . $order->getUser()->getFirstname() . ',
                    
                    Votre commande a changé de statut, elle est maintenant : ' . $message . '.
                    
                    Merci
                    
                    Yann Demor, OnTheSpot CEO');
                
                $mailer->send($email);
            }        

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Modifications de la commande #' . $order->getId() . ' Enregistrées.'
            );
            return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
        }

        return $this->render('back/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="order_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Suppression de la commande #' . $order->getId() . ' effectuée.'
            );
        }

        return $this->redirectToRoute('order_index');
    }
}
