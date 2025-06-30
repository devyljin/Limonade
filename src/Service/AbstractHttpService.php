<?php

namespace Agrume\Limonade\Service;

use Agrume\Limonade\Core\Http\InterserviceRequest;
use Agrume\Limonade\Mediator\MediatorEvent\NullReturnedMediatorEvent;
use Agrume\Limonade\Mediator\MediatorEvent\RequestMediatorEvent;
use Agrume\Limonade\Mediator\MediatorInterface;
use Agrume\Limonade\Mediator\MediatorScopeComponentInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Response\CurlResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Base class for all HTTP service clients.
 */
abstract class AbstractHttpService implements MediatorScopeComponentInterface
{
    protected HttpClientInterface $httpClient;
    protected LoggerInterface $logger;
    protected MediatorInterface $mediator;
    /**
     * Base URL for the service endpoints.
     */
    protected string $baseUrl;
    private $endpoint;

    /**
     * Default request timeout in seconds.
     */
    protected int $defaultTimeout = 10;
    
    /**
     * Default request options.
     */
    protected array $defaultOptions = [];

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->baseUrl = $this->getBaseUrl();
    }

    public function setEndpoint(string $endpoint): self {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function getEndpoint(): string {
        return $this->endpoint;
    }
    public function setMediator(MediatorInterface $mediator): self {
        $this->mediator = $mediator;
        return $this;
    }
    public function getMediator(): MediatorInterface
    {
        return $this->mediator;
    }

    /**
     * Get the base URL for the service.
     * This should be overridden by child classes.
     */
    abstract protected function getBaseUrl(): string;

    /**
     * Send a request to the service.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $endpoint The endpoint to call, relative to the base URL
     * @param array $options Additional request options
     * @return array Response data as an array
     */
    protected function request(string $method, string $endpoint, $options)
        :ResponseInterface
    {

        $this->getMediator()->nautofy(new RequestMediatorEvent($this));

        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $this->logger->info("Sending {$method} request to {$url}", [
            'method' => $method,
            'url' => $url,
            'options' => $options,
        ]);

        $response = $this->httpClient->request($method, $url, $options->toArray());
        $this->logger->info("Received response with status {$response->getStatusCode()}", [
            'statusCode' => $response->getStatusCode(),
        ]);

        return $response;

    }

    /**
     * Process an API request and return a typed response.
     * This method handles validation, authentication, and response creation.
     *
     * @return T The response DTO
     */
    public function processRequest(
        $request,
        string $endpoint ='',
        string $method ='',
        string $responseClass="",
        array $excludeFromPayload = []
    ) {

        $endpoint = $this->getEndpoint();
        $method = $request->getMethod();
        $options =InterserviceRequest::createHttpOptionsFromRequest($request);
        $result = $this->request($method, $endpoint, $options);

        $noRecursive = $this->reduceRecursively($result);
        return $noRecursive;
    }

    function reduceRecursively($result){
        //Hard Check if it's a Response
        if(!is_array($result) && !is_null($result) && get_class($result) ===  "Symfony\Component\HttpFoundation\Response"){
            return $result;
        }

        if($result instanceof JsonResponse ){
            $parsed = self::parseJsonResponse($result);
            if(is_array($parsed)){
                if( array_key_exists("data", $parsed)){
                    //Handle Imbricated JsonResponse into other JsonResponse ¯\_(ツ)_/¯
                    return $this->reduceRecursively((unserialize($parsed["data"]))->autoMount());
                }
            } else {
                $this->getMediator()->nautofy(new NullReturnedMediatorEvent($this));
                return $result;
            }
        }
        else if($result instanceof CurlResponse){
                // manage CurlResponseType
                $jsonContent = json_decode($result->getContent(true), true);
                if(!is_null($jsonContent)){
                    return $this->reduceRecursively(json_decode($result->getContent(true), true));
                } else if(count($response = $this->splitHtmlAndData($result->getContent())) > 0){
                    // Handle dump function
                    return new Response($response[0], 501);
                }

        }
        if(is_array($result)) {
            if(array_key_exists("nativeException",$result)){
                //Handle Parse Errors Return Recursively ┬─┬ノ( º _ ºノ)
                $nativeException = $result["nativeException"];
                return new JsonResponse($nativeException, $nativeException["code"], [], false);
            }

            if(array_key_exists("data",$result)){
                //Deserialize if is response DTO
                $dto = unserialize($result['data']);
                return $this->reduceRecursively( $dto->autoMount());
            }

            if(array_key_exists("exception",$result)){
                //Throw if is an exception  (╯°□°)╯︵ ┻━┻
                throw unserialize($result['exception'])->autoMount();
            }
        }
        else if(method_exists($result, 'toArray') ) {
            $this->reduceRecursively($result->toArray());
        } else if(method_exists($result, 'getContent')){
            $this->reduceRecursively( json_decode($result->getContent(), true));
        }
        return $result;
    }

    public function splitHtmlAndData(string $content): array {
        if (preg_match('/(.*<\/script>|.*<\/pre>)(.*)/is', $content, $matches)) {
            return [trim($matches[1]), trim($matches[2])];
        }
        return ['', trim($content)];
    }

    /**
     * Parse a JsonResponse and return its decoded content as an array.
     *
     * @param JsonResponse $response
     * @return array|null
     */
    static function parseJsonResponse(JsonResponse $response)
    {
        return json_decode($response->getContent(), true);
    }
}