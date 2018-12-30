<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="get_all_users", methods={ Request::METHOD_GET })
     */
    public function getUsers(): JsonResponse
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return ($users === null)
            ? $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND')
            : new JsonResponse($users, Response::HTTP_OK);
    }

    /**
     * @Route("", name="create_user", methods={Request::METHOD_POST })
     */
    public function createUser(Request $request): JsonResponse
    {

        $bodyJSON = $request->getContent();
        $body = json_decode($bodyJSON, true);
        $username = $body['username'] ?? null;
        $email = $body['email'] ?? null;
        $password = $body['password'] ?? null;
        $enabled = $body['enabled'] ?? false;
        $admin = $body['admin'] ?? false;

        if($username === null || $email === null || $password === null ){
            return $this->createResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'UNPROCESSABLE ENTITIES');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if($user !== null){
            return $this->createResponse(Response::HTTP_BAD_REQUEST, 'USERNAME ALREADY EXISTS');
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if($user !== null){
            return $this->createResponse(Response::HTTP_BAD_REQUEST, 'EMAIL ALREADY EXISTS');
        }

        $user = new User($username, $email, $password, $enabled, $admin);
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="options_users", methods={ Request::METHOD_OPTIONS })
     */
    public function optionsUsers(): JsonResponse
    {
        return $this->createResponse(Response::HTTP_OK, 'GET, POST, DELETE, OPTIONS');
    }

    /**
     * @Route("", name="delete_users", methods={ Request::METHOD_DELETE })
     */
    public function deleteUsers(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        foreach ($users as $user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return new JsonResponse( null, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="get_user_byId", methods={Request::METHOD_GET})
     */
    public function getUserById(User $user): JsonResponse
    {
        return ($user === null)
            ? $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND')
            : new JsonResponse($user, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_user", methods={Request::METHOD_PUT})
     */
    public function updateUser(?User $user, Request $request): JsonResponse
    {
        if ($user === null) {
            return $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND');
        }

        $bodyJSON = $request->getContent();
        $body = json_decode($bodyJSON, true);
        $username = $body['username'] ?? null;
        $email = $body['email'] ?? null;
        $password = $body['password'] ?? null;
        $enabled = $body['enabled'] ?? false;
        $admin = $body['admin'] ?? false;

        if($username === null || $email === null || $password === null ){
            return $this->createResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'UNPROCESSABLE ENTITIES');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $userPersisted = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if($userPersisted !== null){
            return $this->createResponse(Response::HTTP_BAD_REQUEST, 'USERNAME ALREADY EXISTS');
        }

        $userPersisted = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if($userPersisted !== null){
            return $this->createResponse(Response::HTTP_BAD_REQUEST, 'EMAIL ALREADY EXISTS');
        }

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setEnabled($enabled);
        $user->setIsAdmin($admin);

        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse($user, Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/{id}", name="delete_user", methods={Request::METHOD_DELETE})
     */
    public function deleteUserById(?User $user): JsonResponse{
        $entityManager = $this->getDoctrine()->getManager();
        if($user === null) {
            return $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND');
        } else {
            $entityManager->remove($user);
            $entityManager->flush();
            return new JsonResponse( null, Response::HTTP_OK);
        }
    }

    /**
     * @Route("/{id}", name="options_userById", methods={ Request::METHOD_OPTIONS })
     */
    public function optionsUserById(?User $user): JsonResponse
    {
        return $this->createResponse(Response::HTTP_OK, 'GET, PUT, DELETE, OPTIONS');
    }

    /**
     * @param int $statusCode
     * @param string $message
     *
     * @return JsonResponse
     */
    private function createResponse(int $statusCode, string $message): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => [
                    'code' => $statusCode,
                    'message' => $message
                ]
            ],
            $statusCode
        );
    }
}
