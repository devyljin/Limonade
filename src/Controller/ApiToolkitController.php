<?php

namespace Agrume\Limonade\Controller;

use Agrume\Limonade\Controller\AutomatedApiControllerInterface;
use Agrume\Limonade\Http\InterserviceRequest;
use Agrume\Limonade\Mediator\Repository\ExternalApiRepository;
use Agrume\Limonade\Service\AbstractHttpService;
use Agrume\Limonade\Service\ServiceAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\UuidV4;
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
abstract class ApiToolkitController extends AbstractController implements AutomatedApiControllerInterface
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
    }
    protected function me(){
        if(UuidV4::isValid($this->getUser()->getId())){
            return $this->getUser()->getId()->jsonSerialize();
        }
        return null;
    }
    public function internalGetResource(Request $request, $serviceName, $id)
    {
        return $this->interserviceApiRequest($request, "/api/v1/$serviceName/generic/$id");
    }
    public function internalListResource(Request $request, $serviceName)
    {
        return $this->interserviceApiRequest($request, "/api/v1/$serviceName/generic");
    }
    public function internalPostResource(Request $request, $serviceName,$body)
    {
        return $this->interserviceApiRequest($request, "/api/v1/$serviceName/generic", $body, "POST");
    }
    public function internalPutResource(Request $request, $serviceName, $id, $body)
    {
        return $this->interserviceApiRequest($request, "/api/v1/$serviceName/generic/$id", $body, "PUT");
    }

    public function internalPatchResource(Request $request, $serviceName, $id, $body)
    {
        return $this->interserviceApiRequest($request, "/api/v1/$serviceName/generic/$id", $body, "PATCH");
    }
    public function internalDeleteResource(Request $request, $serviceName, $id)
    {
        return $this->interserviceApiRequest($request, "/api/v1/$serviceName/generic/$id", [], "DELETE");
    }



    public function getEntityClassName():string
    {
        return $this->entityClassName;
    }
    /**
     * Calls another service's API and returns a repository instance based on the response.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Incoming request
     * @param string $serviceName Name of the microservice to target
     * @param string $endpoint Optional endpoint to hit (default: "list")
     * @return ExternalApiRepository Repository built from returned data
     */
    public function getCustomInternalApiDataRepository(Request $request, $serviceName, $endpoint = "list"){
        $data = $this->interserviceApiRequest($request, "/api/v1/$serviceName/$endpoint");
        return new ExternalApiRepository($data);
    }
    /**
     * Validates a given entity and returns a JSON error response if validation fails.
     *
     * @param object $entity The object to validate
     * @return JsonResponse|bool Returns false if valid, or JsonResponse with errors
     */
    public function validate($entity) : JsonResponse|bool
    {
        $errors = $this->validator->validate($entity);
        if($errors->count() > 0){
            return new JsonResponse($this->serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [],true);
        }
        return false;
    }
    /**
     * Creates and sends an inter-service HTTP request using the ServiceAdapter.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Original HTTP request
     * @param string $to Target internal endpoint (e.g., /api/v1/service/endpoint)
     * @param array $params Parameters to pass along with the request
     * @param string $method HTTP method to use (default: "GET")
     * @return array Decoded JSON response from the target service
     */
    public function interserviceApiRequest(Request $request, $to, $params = [], $method = "GET"){
        $iReq = (new InterserviceRequest())
            ->from($request)
            ->params($params)
            ->setMethod($method)
            ->to($to);
        $req = $this->serviceAdapter->autoRequest($iReq);
        return AbstractHttpService::parseJsonResponse($req);
    }

    protected function getRepository()
    {
        return $this->entityManager->getRepository($this->getEntityClassName());
    }

    protected function jsonResponse($data, int $status = JsonResponse::HTTP_OK, array $groups = []): JsonResponse
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


