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
            ->text('Bonjour ' . $user->getFirstname() . ',
            Nous sommes heureux de vous compter parmi notre client. Pour confirmer votre adresse email, merci de cliquer sur le lien suivant :
            https://onthespot.link/back/public/confirm/' . $token . '
            
            Si vous avez des questions, n\'hésitez pas à nous contacter en reponse a cet email.
            
            Merci
            
            Yann Demor, OnTheSpot CEO')
            ->html('<!DOCTYPE html>
            <html lang="fr">
            
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Confirmation de votre email</title>
            </head>
            
            <body style="font-family: Arial, Helvetica, sans-serif; max-width: 576px; margin: auto;">
                <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 1rem;">
                    <h1 style="margin: auto;">Bonjour ' . $user->getFirstname() . '</h1>
            
                    <p style="margin: 1rem auto;">Nous sommes heureux de vous compter parmi notre client. Pour confirmer votre adresse email, merci de cliquer sur le bouton suivant :</p>
            
                <a href="https://onthespot.link/back/public/confirm/' . $token . '" style="margin: auto; padding: 1rem 2rem; background-color: #348eda; color: #fff;">Confirmez votre adresse email</a>
                </div>
            
                <p>Ou copiez collez le lien suivant dans la barre d\'adresse de votre navigateur : <a href="https://onthespot.link/back/public/confirm/' . $token . '">https://onthespot.link/back/public/confirm/' . $token . '</a></p>
            
                <p>Si vous avez des questions, n\'hésitez pas à nous contacter en reponse à cet email.</p>
            
                <p>Merci</p>
                <p>Yann Demor, OnTheSpot CEO</p>
            
            </body>
            
            </html>');

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
     * @Route("/api/users", name="api_user_read", methods="GET")
     */
    public function read(Security $security): Response
    {
        $user = $security->getUser();
        return $this->json($user, 200, [], ['groups' => [
            'api_user_edit_and_read',
        ]]);
    }


    /**
     * Edit User's details
     *
     * @Route("/api/users", name="api_user_edit", methods={"PUT"})
     */
    public function edit(
        Security $security,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();

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

        // Then we can save the details in the database
        $entityManager->flush();

        return $this->json(['message' => 'Utilisateur modifié.'], Response::HTTP_OK);
    }
}
