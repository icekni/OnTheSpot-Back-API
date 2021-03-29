<?php

namespace App\Controller\Back;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\FileUploader;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {

        return $this->render('back/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Handling the picture
            $picture = $form->get('picture')->getData();
            $newPicture = $fileUploader->upload($picture);
            $product->setPicture($newPicture);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Création de "' . $product->getName() . '" effectuée.'
            );

            return $this->redirectToRoute('product_index');
        }

        return $this->render('back/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('back/product/show.html.twig', [
            'product' => $product,
        ]);
    }


    /**
     * 
     * @Route("/search", name="product_searched_show", methods="POST")
     */
    public function showSearched(ProductRepository $productRepository, Request $request): Response
    {
        // We get the result of the request search bar form 
        $search = $request->request->get('search');

        // We get the requested object with our custom request
        $product = $productRepository->findProduct($search);

        return $this->render('back/product/show.html.twig', [
            'product' => $product,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Handling the picture
            $picture = $form->get('picture')->getData();
            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $product->setPicture($newPicture);
            }
            // Same for the thumbnail
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $newThumbnail = $fileUploader->upload($picture);
                $product->setThumbnail($newThumbnail);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Modifications de "' . $product->getName() . '" enregistrées.'
            );

            return $this->redirectToRoute('product_index');
        }

        return $this->render('back/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Suppression du produit "' . $product->getName() . '" effectuée.'
            );
        }

        return $this->redirectToRoute('product_index');
    }
}
