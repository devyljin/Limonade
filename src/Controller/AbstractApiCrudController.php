<?php

namespace Agrume\Limonade\Controller;

use Agrume\Limonade\Controller\ApiToolkitController;
use Agrume\Limonade\Controller\AutomatedApiControllerInterface;
use Agrume\Limonade\Service\ServiceAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ApiToolkitController
 *
 * A base controller providing utility methods for:
 * - Inter-service communication
 * - Validation logic
 * - Repository creation from external API responses
 *
 * @package Agrume\Limonade\Controller
 */
abstract class AbstractApiCrudController extends ApiToolkitController implements AutomatedApiControllerInterface
{
    protected string $entityClassName;

    /**
     * @param EntityManagerInterface $entityManager Handles updating entities
     * @param ServiceAdapter $serviceAdapter Handles outgoing service requests
     * @param ValidatorInterface $validator Symfony validator for entity validation
     * @param SerializerInterface $serializer Serializer for error serialization in this case, and json serialization/deserialization in childrens
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ServiceAdapter $serviceAdapter,
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer
    )
    {
        parent::__construct($entityManager, $serviceAdapter, $validator, $serializer);
    }

    public function index(): JsonResponse
    {
        $entity = $this->getRepository()->findAll();
        return $this->jsonResponse($entity);
    }

    public function show(string $id): JsonResponse
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            return $this->jsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->jsonResponse($entity);
    }

    public function create(Request $request): JsonResponse
    {
        $entity = $this->serializer->deserialize(
            $request->getContent(),
            $this->getEntityClassName(),
            'json'
        );
        $this->validate($entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->jsonResponse($entity, Response::HTTP_CREATED);
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            return $this->jsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $this->serializer->deserialize(
            $request->getContent(),
            $this->getEntityClassName(),
            'json',
            ['object_to_populate' => $entity]
        );

        $this->validate($entity);

        $this->entityManager->flush();

        return $this->jsonResponse($entity);
    }

    public function patch(string $id, Request $request): JsonResponse
    {
        return $this->update($id, $request);
    }

    public function delete(string $id): JsonResponse
    {
        $book = $this->getRepository()->find($id);

        if (!$book) {
            return $this->jsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}


