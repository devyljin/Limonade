<?php

namespace Agrume\Limonade\DTO\Event\Response;

use Agrume\Limonade\DTO\AbstractDTOAdapter;
use Agrume\Limonade\DTO\Component\HttpFoundation\ResponseHeaderBagDTO;
use Agrume\Limonade\DTO\Event\Response\JsonResponseEventDTO;
use Agrume\Limonade\DTO\Event\Response\ResponseEventDTO;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseEventDTOAdapter extends AbstractDTOAdapter
{
    protected Response $response;
    public function __construct(Response $response){

        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function adaptee(): Object
    {
        $classShortname = (new \ReflectionClass($this->response))->getShortName();

        $this->setShortName($classShortname);


        $methodName ='adaptee' . $this->getShortName();

        if(method_exists(self::class, $methodName)){
            return $this->$methodName($this->response);
        }

        throw new Exception("No adapter method found for {$this->getShortName()}");
    }

    private function adapteeResponse(Response $response): ResponseEventDTO
    {
        $dto = new ResponseEventDTO();
        $dto->setShortName($this->getShortName());
        $payload = [
            $response->getContent(),
            $response->getStatusCode(),
            new ResponseHeaderBagDTO($response->headers)
        ];
        $dto->setPayload($payload);
        return $dto;
    }

    private function adapteeJsonResponse(JsonResponse $response): JsonResponseEventDTO
    {
        $dto = new JsonResponseEventDTO();
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$response::class]);
        $payload = [
            $response->getContent(),
            $response->getStatusCode(),
            new ResponseHeaderBagDTO($response->headers),
            true
        ];

        $dto->setPayload($payload);
        return $dto;
    }
}