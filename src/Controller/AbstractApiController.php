<?php

namespace Agrume\Limonade\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator
    ) {}

    protected function jsonResponse($data, int $status = Response::HTTP_OK, array $groups = []): JsonResponse
    {
        $context = [];
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        $json = $this->serializer->serialize($data, 'json', $context);
        return new JsonResponse($json, $status, [], true);
    }

    protected function validateEntity($entity, array $groups = null): array
    {
        $violations = $this->validator->validate($entity, null, $groups);
        $errors = [];

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }


}