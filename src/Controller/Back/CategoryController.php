<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\FileUploader;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Handling the picture
            $picture = $form->get('picture')->getData();
            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $category->setPicture($newPicture);
            }
            // Same for the thumbnail
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $newThumbnail = $fileUploader->upload($picture);
                $category->setThumbnail($newThumbnail);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Création de la catégorie "' . $category->getTitle() . '" effectuée.'
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render('back/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('back/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump("coucou");

            // Handling the picture
            $picture = $form->get('picture')->getData();
            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $category->setPicture($newPicture);
            }
            // Same for the thumbnail
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $newThumbnail = $fileUploader->upload($picture);
                $category->setThumbnail($newThumbnail);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Modification de la catégorie "' . $category->getTitle() . '" enregistrées.'
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render('back/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Suppression de la catégorie "' . $category->getTitle() . '" effectuée.'
            );
        }

        return $this->redirectToRoute('category_index');
    }
}
