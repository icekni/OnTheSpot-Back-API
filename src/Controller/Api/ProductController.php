<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * Read one product
     * 
     * @Route("/api/products/{id<\d+>}", name="api_read_one_product", methods={"GET"})
     */
    public function read(Product $product): Response
    {
        // We send a custom message if product not found (404)
        if ($product === null) {

            // Optionnel, message pour le front
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Film non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, 200);
    }
}
