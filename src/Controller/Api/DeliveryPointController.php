<?php

namespace App\Controller\Api;

use App\Repository\DeliveryPointRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeliveryPointController extends AbstractController
{
    /**
     * @Route("/api/delivery-points", name="api_delivery_point")
     */
    public function browse(DeliveryPointRepository $deliveryPointRepository): Response
    {
        $deliveryPoints = $deliveryPointRepository->findAll();

        return $this->json($deliveryPoints, 200);
    }
}
