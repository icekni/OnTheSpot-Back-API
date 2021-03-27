<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
            200,
            [],
            ['groups' => [
                'api_user_browse_and_read'
            ]]
        );
    }

    /**
     * Registering of a user
     * 
     * @Route("/api/users", name="api_user_add", methods="POST")
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, MailerInterface $mailer): Response
    {
        // Read the content of the request
        $jsonContent = $request->getContent();

        // Turn it into an object User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // We need a random token to confirm user email
        $token = bin2hex(random_bytes(10));
        // Store the token in $user
        $user->setToken($token);
        $user->setRoles(["ROLE_USER"]);

        // Validation
        $errors = $validator->validate($user);

        // In case of error
        if (count($errors) > 0) {
            // Send a json containing all errors
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        // Send a confirmation email
        $email = (new Email())
            ->from(new Address('onthespot@apotheoz.tech', 'OnTheSpot'))
            ->to($user->getEmail())
            ->subject('Confirmation d\'inscription sur le site OnTheSpot')
            ->text('Bonjour' . $user->getFirstname() . '\nPour confirmer votre inscription, veuillez cliquer sur ce lien : https://onthespot.apotheoz.tech/confirm/' . $token)
            ->html('<p>Bonjour' . $user->getFirstname() . '</p><p> Pour confirmer votre inscription, veuillez cliquer sur ce lien : <a href="https://onthespot.apotheoz.tech/confirm/' . $token . '">Confirmation email</a>');
        
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // $user->setStatus(true);
        }
        
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

    /**
     * Read user's details 
     * 
     * @Route("/api/users/{id<\d+>}", name="api_user_read", methods="GET")
     */
    public function read(
        Security $security,
        User $user = null
    ): Response {
        // We send a custom message if order not found (404)
        if ($user === null) {

            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Utilisateur non existant.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        // We get the id of the user we want to see the details
        $idUserToDetail = $user->getId();
        // We get the connected user's id
        $connectedUser = $security->getUser();
        $userId = $connectedUser->getId();

        // If the connected user's id is the same than one from the user we want to see the details 
        if ($userId === $idUserToDetail) {
            return $this->json(
                $user,
                200,
                [],
                ['groups' => [
                    'api_user_browse_and_read'
                ]]
            );
        } else {
            $message = [
                'status' => Response::HTTP_FORBIDDEN,
                'error' => 'Action réservée à l\'utilisateur',
            ];

            return $this->json($message, Response::HTTP_FORBIDDEN);
        }
    }


    /**
     * Edit User's details
     *
     * @Route("/api/users/{id<\d+>}", name="api_user_edit", methods={"PATCH"})
     */
    public function edit(
        Security $security,
        User $user = null,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): Response {
        // We send a custom message if order not found (404)
        if ($user === null) {

            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Utilisateur non existant.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        // Getting the JSON content of the request
        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            User::class,
            'json',
            // We indicate the serializer which entity to modify
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        // Validation   
        $errors = $validator->validate($user);
        // In case of error
        if (count($errors) > 0) {
            // Send a json containing all errors
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We get the id of the user we want to modify
        $idUserToModify = $user->getId();
        // We get the connected user's id
        $connectedUser = $security->getUser();
        $userId = $connectedUser->getId();

        // If the connected user's id is the same than one from the user we want to modify 
        if ($userId === $idUserToModify) {
            // Then we can save the details in the database
            $entityManager->flush();

            return $this->json(['message' => 'Utilisateur modifié.'], Response::HTTP_OK);
        } else {
            $message = [
                'status' => Response::HTTP_FORBIDDEN,
                'error' => 'Action réservée à l\'utilisateur',
            ];

            return $this->json($message, Response::HTTP_FORBIDDEN);
        }
    }
}
