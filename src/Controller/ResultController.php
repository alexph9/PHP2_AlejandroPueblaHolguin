<?php

namespace App\Controller;

use App\Entity\Result;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/results")
 */
class ResultController extends AbstractController
{
    /**
     * @Route("", name="get_all_results", methods={ Request::METHOD_GET })
     */
    public function getResults(): JsonResponse
    {
        $results = $this->getDoctrine()
            ->getRepository(Result::class)
            ->findAll();

        return ($results === null)
            ? $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND')
            : new JsonResponse($results, Response::HTTP_OK);
    }

    /**
     * @Route("", name="create_result", methods={Request::METHOD_POST })
     */
    public function createResult(Request $request): JsonResponse
    {

        $bodyJSON = $request->getContent();
        $body = json_decode($bodyJSON, true);
        $resultValue = $body['result'] ?? null;
        $userId = $body['user'] ?? null;
        $time = new \DateTime('now');

        if($resultValue === null || $userId === null){
            return $this->createResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'UNPROCESSABLE ENTITIES');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if($user === null){
            return $this->createResponse(Response::HTTP_NOT_FOUND, 'USER NOT FOUND');
        }

        $result = new Result($resultValue, $user, $time);
        $entityManager->persist($result);
        $entityManager->flush();
        return new JsonResponse($result, Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="options_results", methods={ Request::METHOD_OPTIONS })
     */
    public function optionsResults(): JsonResponse
    {
        return $this->createResponse(Response::HTTP_OK, 'GET, POST, DELETE, OPTIONS');
    }

    /**
     * @Route("", name="delete_results", methods={ Request::METHOD_DELETE })
     */
    public function deleteResults(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $results = $this->getDoctrine()
            ->getRepository(Result::class)
            ->findAll();

        foreach ($results as $result) {
            $entityManager->remove($result);
            $entityManager->flush();
        }
        return new JsonResponse( null, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="get_result_byId", methods={Request::METHOD_GET})
     */
    public function getResultById(Result $result): JsonResponse
    {
        return ($result === null)
            ? $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND')
            : new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_result", methods={Request::METHOD_PUT})
     */
    public function updateResult(?Result $result, Request $request): JsonResponse
    {
        if ($result === null) {
            return $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND');
        }

        $bodyJSON = $request->getContent();
        $body = json_decode($bodyJSON, true);
        $resultValue = $body['result'] ?? null;
        $userId = $body['user'] ?? null;
        $time = new \DateTime('now');

        if($userId === null || $resultValue === null){
            return $this->createResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'UNPROCESSABLE ENTITIES');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if($user === null){
            return $this->createResponse(Response::HTTP_NOT_FOUND, 'USER NOT FOUND');
        }

        $result->setResult($resultValue);
        $result->setUser($user);
        $result->setTime($time);

        $entityManager->persist($result);
        $entityManager->flush();
        return new JsonResponse($result, Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/{id}", name="delete_result", methods={Request::METHOD_DELETE})
     */
    public function deleteResultById(?Result $result): JsonResponse{
        $entityManager = $this->getDoctrine()->getManager();
        if($result === null) {
            return $this->createResponse(Response::HTTP_NOT_FOUND, 'NOT FOUND');
        } else {
            $entityManager->remove($result);
            $entityManager->flush();
            return new JsonResponse( null, Response::HTTP_OK);
        }
    }

    /**
     * @Route("/{id}", name="options_resultById", methods={ Request::METHOD_OPTIONS })
     */
    public function optionsResultById(?Result $result): JsonResponse
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