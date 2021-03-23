<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * Useless and dangerous route, for debug only
     * 
     * @Route("/api/users", name="api_user_browse", methods="GET")
     */
    public function browse(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->json(
            $users, 
            200
        );
    }

    /**
     * Registering of a user
     * 
     * @Route("/api/users", name="api_user_add", methods="POST")
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder): Response
    {
        // Read the content of the request
        $jsonContent = $request->getContent();

        // Turn it into an object User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // TODO validation
        // TODO send a confirmation email
        
        // Saving into DB
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            $user,
            Response::HTTP_CREATED
        );
    }

    /**
     * Deletion of an account
     * 
     * @Route("/api/users", name="api_user_delete", methods="DELETE")
     */
    public function delete(Security $security, EntityManagerInterface $manager): Response
    {
        // Geeting the current user
        $user = $security->getUser();

        // Checking if the user really exists
        // You should never see that because that route is protected by an ACL, so you can't access this route without a connected user
        if (null === $user) {
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Aucun utilisateur avec ce compte n\'existe .',
            ];

            // Display an error message
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        // Deletion in DB
        $manager->remove($user);
        $manager->flush();

        return $this->json($user, Response::HTTP_OK);
    }
}
