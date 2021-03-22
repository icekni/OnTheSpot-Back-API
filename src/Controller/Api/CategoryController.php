<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * Browse all the categories
     * 
     * @Route("/api/categories", name="api_categories", methods={"GET"})
     */
    public function browse(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->json($categories, 200);
    }

    /**
     * Read one category
     * 
     * @Route("/api/categories/{id<\d+>}", name="api_categories_read_one", methods={"GET"})
     */
    public function read(Category $category = null, ProductRepository $productRepository): Response
    {
        // We send a custom message if category not found (404)
        if ($category === null) {
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Catégorie non trouvée.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        $products = $productRepository->findBy(['category' => $category]);

        return $this->json($products, 200);
    }
}
