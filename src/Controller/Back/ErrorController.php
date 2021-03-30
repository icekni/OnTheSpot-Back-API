<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/back/error", name="back_error")
     */
    public function index(): Response
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->render('back/error/index.html.twig');
=======
        return $this->render('back/error/index.html.twig', [
            'controller_name' => 'ErrorController',
        ]);
>>>>>>> 5ebaf75... CREATE: 404 page unfinished
=======
        return $this->render('back/error/index.html.twig');
>>>>>>> 3d11531... UPDATE: 404-403 pages done
    }
}
