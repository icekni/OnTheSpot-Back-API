<?php

namespace App\Controller\Back;

use App\Entity\DeliveryPoint;
use App\Form\DeliveryPointType;
use App\Repository\DeliveryPointRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deliverypoint")
 */
class DeliveryPointController extends AbstractController
{
    /**
     * @Route("/", name="delivery_point_index", methods={"GET"})
     */
    public function index(DeliveryPointRepository $deliveryPointRepository): Response
    {
        return $this->render('back/delivery_point/index.html.twig', [
            'delivery_points' => $deliveryPointRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="delivery_point_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $deliveryPoint = new DeliveryPoint();
        $form = $this->createForm(DeliveryPointType::class, $deliveryPoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // We need to find the city matching this location
            // First we need to explode location to have real latitude and longitude
            $coord = explode(', ', $deliveryPoint->getLocation());
            // Call to an gouv API
            $result = file_get_contents('https://api-adresse.data.gouv.fr/reverse/?lon=' . $coord[1] . '&lat=' . $coord[0] . '');
            $data = json_decode($result);

            if (isset($data->features[0])) {
                $deliveryPoint->setCity(
                    $data->features[0]
                        ->properties
                        ->city
                );
            } else {
                $deliveryPoint->setCity("Ville inconnue");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($deliveryPoint);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Cr??ation du point de retrait "' . $deliveryPoint->getName() . '" effectu??e.'
            );

            return $this->redirectToRoute('delivery_point_index');
        }

        return $this->render('back/delivery_point/new.html.twig', [
            'delivery_point' => $deliveryPoint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="delivery_point_show", methods={"GET"})
     */
    public function show(DeliveryPoint $deliveryPoint): Response
    {
        return $this->render('back/delivery_point/show.html.twig', [
            'delivery_point' => $deliveryPoint,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="delivery_point_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DeliveryPoint $deliveryPoint): Response
    {
        $form = $this->createForm(DeliveryPointType::class, $deliveryPoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We need to find the city matching this location
            // First we need to explode location to have real latitude and longitude
            $coord = explode(', ', $deliveryPoint->getLocation());
            // Call to an gouv API
            $result = file_get_contents('https://api-adresse.data.gouv.fr/reverse/?lon=' . $coord[1] . '&lat=' . $coord[0] . '');
            $data = json_decode($result);

            if (isset($data->features[0])) {
                $deliveryPoint->setCity(
                    $data->features[0]
                        ->properties
                        ->city
                );
            } else {
                $deliveryPoint->setCity("Ville inconnue");
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Modifications du point de retrait "' . $deliveryPoint->getName() . '" enregistr??es.'
            );

            return $this->redirectToRoute('delivery_point_index');
        }

        return $this->render('back/delivery_point/edit.html.twig', [
            'delivery_point' => $deliveryPoint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="delivery_point_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DeliveryPoint $deliveryPoint): Response
    {
        if ($this->isCsrfTokenValid('delete' . $deliveryPoint->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($deliveryPoint);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Suppression du point de retrait "' . $deliveryPoint->getName() . '" effectu??e.'
            );
        }

        return $this->redirectToRoute('delivery_point_index');
    }
}
