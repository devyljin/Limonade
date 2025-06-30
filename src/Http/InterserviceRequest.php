<?php

namespace Agrume\Limonade\Http;

use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InterserviceRequest
 *
 * This class is responsible for building and simulating HTTP requests internally
 * (e.g., for inter-service communication or request forwarding).
 *
 * @package Agrume\Limonade\Http
 */
class InterserviceRequest
{
    // Endpoint of the new request
    private $endpoint = "/help";
    private $methodData = "GET";
    private array $parametersData = [];
    private array $cookiesData = [];
    private array $filesData = [];
    private array $serverData = [];
    private string $contentData = "";

    private array $headersData = [];
    /**
     * Establish the minimum to get parameter from the gateway
     *
     * @param Request $request
     * @param $translate
     * @return $this
     */
    public function from(
        Request $request,
        $translate = false
    )
    {
        if($translate === true){
           return $this->translate($request);
        }
        $this->serverData = $request->server->all();
        return $this;
    }
    /**
     * Translate a Symfony Request into internal representation (method, params, cookies, etc.)
     *
     * @param Request $request
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function translate(Request $request): \Agrume\Limonade\Http\InterserviceRequest
    {

        $this->serverData = $request->server->all();
        $this->methodData = $request->getMethod();
        $this->parametersData = $request->request->all();
        $this->parametersData = array_merge_recursive( $this->parametersData, $request->query->all());
        $this->cookiesData = $request->cookies->all();
        $this->filesData = $request->files->all();
        $this->contentData = $request->getContent();
        return $this;
    }
    /**
     * Set or merge request parameters.
     *
     * @param array $params
     * @param bool $replace Whether to overwrite or merge
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function params(array $params, $replace = false ): \Agrume\Limonade\Http\InterserviceRequest
    {
        if($replace === true){
            $this->parametersData = $params;
        }
        else{
            $this->parametersData = array_merge_recursive($this->parametersData ,$params);
        }
        return $this;
    }
    /**
     * Set or merge cookie data.
     *
     * @param array $cookies
     * @param bool $replace Whether to overwrite or merge
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function cookies(array $cookies, $replace = false): \Agrume\Limonade\Http\InterserviceRequest
    {
        if($replace === true){
            $this->cookiesData = $cookies;
        }
        else{
            $this->cookiesData = array_merge_recursive($this->cookiesData ,$cookies);
        }
        return $this;
    }
    /**
     * Set or merge uploaded file data.
     *
     * @param array $files
     * @param bool $replace Whether to overwrite or merge
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function files(array $files, $replace = false): \Agrume\Limonade\Http\InterserviceRequest
    {
        if($replace === true){
            $this->filesData = $files;
        }
        else{
            $this->filesData = array_merge_recursive($this->filesData ,$files);
        }
        return $this;
    }

    /**
     * Shortcut to configure a GET request.
     *
     * @param array|null $params overwrite params
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function get(?array $params = null): \Agrume\Limonade\Http\InterserviceRequest{
        if(!is_null($params)){
            $this->params($params, true);
        }
        $this->setMethod("GET");
        return $this;
    }
    /**
     * Shortcut to configure a POST request.
     *
     * @param array|null $params overwrite params
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function post(?array $params = null): \Agrume\Limonade\Http\InterserviceRequest{
        if(!is_null($params)){
            $this->params($params, true);
        }
        $this->setMethod("POST");

        return $this;
    }

    /**
     * Shortcut to configure a PUT request.
     *
     * @param array|null $params overwrite params
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function put(?array $params = null): \Agrume\Limonade\Http\InterserviceRequest{
        if(!is_null($params)){
            $this->params($params, true);
        }
        $this->setMethod("PUT");

        return $this;
    }
    /**
     * Shortcut to configure a PATCH request.
     *
     * @param array|null $params overwrite params
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function patch(?array $params = null): \Agrume\Limonade\Http\InterserviceRequest{
        if(!is_null($params)){
            $this->params($params, true);
        }
        $this->setMethod("PATCH");
        return $this;
    }
    /**
     * Shortcut to configure a DELETE request.
     *
     * @param array|null $params overwrite params
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function delete(?array $params = null): \Agrume\Limonade\Http\InterserviceRequest{
        if(!is_null($params)){
            $this->params($params, true);
        }
        $this->setMethod("DELETE");
        return $this;
    }
    /**
     * Set the HTTP method manually.
     *
     * @param string $method POST|GET|PUT|PATCH|DELETE|OPTIONS....
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function setMethod(string $method): \Agrume\Limonade\Http\InterserviceRequest{
        $this->methodData = $method;
        return $this;
    }
    /**
     * Set the raw content of the request.
     *
     * @param string $content
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function content(string $content): \Agrume\Limonade\Http\InterserviceRequest
    {
        $this->contentData = $content;
        return $this;
    }

    /**
     * Set or merge server parameters (typically includes headers).
     *
     * @param array $server
     * @param bool $replace Whether to overwrite or merge
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function server(array $server, $replace = false): \Agrume\Limonade\Http\InterserviceRequest
    {
        if($replace === true){
            $this->serverData = $server;
        }
        else{
            $this->serverData = array_merge_recursive($this->serverData ,$server);
        }
        return $this;
    }
    /**
     * Add headers to the request (converted to server format).
     *
     * @param array $header
     * @param bool $replace Whether to overwrite or merge
     * @return \Agrume\Limonade\Http\InterserviceRequest
     */
    public function header(array $header, $replace = false): \Agrume\Limonade\Http\InterserviceRequest
    {

        $server = [];

        foreach ($header as $key => $value) {
            $key = strtoupper(str_replace('-', '_', $key));

            if (!in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $key = 'HTTP_' . $key;
            }

            $server[$key] = is_array($value) ? $value[0] : $value;
        }

        if($replace === true){
            $this->serverData = $server;
        }
        else{
            $this->serverData = array_merge_recursive($this->serverData ,$server);
        }
        return $this;
    }


    /**
     * Define the target endpoint and optionally generate the request.
     *
     * @param string $endpoint
     * @param bool $end If true, generate the Symfony Request object
     * @return \Agrume\Limonade\Http\InterserviceRequest|Request
     */
    public function to(string $endpoint, $end = true) {
        $this->endpoint = $endpoint;
        if($end === true){
            return $this->generate();
        }
        return $this;
    }

    /**
     * Build and return a Symfony Request object based on internal data.
     *
     * @return Request
     */
    public function generate(){

        $req = Request::create(
            $this->endpoint,
            $this->methodData,
            $this->parametersData,
            $this->cookiesData,
            $this->filesData,
            $this->serverData,
            $this->contentData
        );
        return $req;
    }

    /**
     * Convert a Symfony Request into a Symfony HttpOptions object.
     *
     * @param Request $request
     * @return HttpOptions
     */
    static function createHttpOptionsFromRequest(Request $request): HttpOptions
    {
        $httpOptions = new HttpOptions();

        $httpOptions->setHeaders($request->headers->all());
        $httpOptions->setBody($request->getContent());
        $httpOptions->setQuery($request->query->all());

        // Implement all these ?
//        dd(
//            $request->getContentTypeFormat(),
//            $request->getTrustedProxies(),
//            $request->getTrustedHeaderSet(),
//            $request->getUser(),
//            $request->getPassword(),
//            $request->getLocale(),
//        );

        return  $httpOptions;
    }
}

// Interservice Map: By DevilJinx

//⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣀⣀⣀⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//Your service is here -->⣀⣤⡴⠶⠞⠛⠛⠉⠉⠉⠉⠉⠉⠛⠛⠶⢦⣄⡀⠀⠀⠀
//⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣠⣤⣶⣾⠿⠛⠉⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⠀⠀⠀⠈⠛⢦⡀⠀
//⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣤⠶⢛⣩⠶⠛⠉⠀⠀⠀⣀⣤⡴⠶⠚⠛⠛⠛⠉⠛⠛⠛⢶⡟⠉⢻⡄⠀⠀⠀⠈⢻⡄
//⠀⠀⠀⠀⠀⠀⠀⣠⡴⠟⢉⣠⠶⠋⠁⠀⠀⣠⡴⠞⠋⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⠷⡤⠾⣇⠀⠀⠀⠀⠀⣿
//⠀⠀⠀⠀⣠⡴⠛⠁⣀⡴⠛⠁⠀⢀⣠⠶⠛⠁⠀⠀⠀⣀⣠⡤⠶⠒⠛⠛⠛⠛⠛⠶⣤⡀⠀⠀⠀⢹⡆⠀⠀⠀⠀⢸
//⠀⢀⣴⠟⠁⠀⣠⡾⠋⠀⠀⢀⡴⠛⠁⠀⢰⠞⠳⡶⠛⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⣷⠀⠀⠀⢈⡇⠀⠀⠀⠀⣾
//⢴⠟⠁⠀⢀⡼⠋⠀⠀⢀⡴⠋⠀⠀⠀⣠⡾⠷⠶⠇⢀⣠⣤⠶⠖⠲⢶⣄⠀⠀⠀⠀⠀⡿⠀⠀⠀⢸⡇⠀⠀⠀⢰⡏
//⠀⠀⠀⣰⠟⠀⠀⠀⣴⠏⠀⠀⠀⣠⠞⠉⠀⠀⣠⡶⠋⠁⠀⠀⠀⠀⢀⡿⠀⠀⠀⠀⣼⠃⠀⠀⢀⡟⠂⠀⠀⢠⡟⠀
//⠀⢀⣼⠋⠀⠀⢀⡾⠁⠀⠀⢠⡞⠁⠀⠀⢠⡾⠁⠀⠀⠀⠀⣀⣀⣠⡾⠁⠀⠀⣠⡾⠁<---⢠⡞⠁--⣰⠟-------You are here.⠀⠀
//⠀⣾⠃⠀⢠⡟⠛⣷⠂⠀⢠⡟⠀⠀⠀⠀⢾⡀⠀⠀⠀⠀⣸⣏⣹⡏⠀⠀⣠⡾⠋⠀⠀⢀⣴⠏⠀⠀⢀⡼⠋⠀⠀⠀
//⣸⠇⠀⠀⠈⢻⡶⠛⠀⠀⣿⠀⠀⠀⠀⠀⠈⠛⠲⠖⠚⠋⠉⠉⠉⣀⣤⠞⠋⠀⠀⢀⣴⠟⠁⠀⠀⣰⠟⠁⠀⣴⠆⠀
//⣿⠀⠀⠀⠀⢸⡇⠀⠀⠀⢻⣆⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣤⠶⠛⠉⣀⣀⡀⣀⡴⠟⠁⠀⢀⣤⠞⠁⢀⣴⠟⠁⠀⠀
//⣿⠀⠀⠀⠀⠘⣧⠀⠀⠀⠀⠙⠳⠶⠤⣤⠤⠶⠶⠚⠋⠉⠀⠀⠀⡟⠉⠈⢻⡏⠀⠀⣀⡴⠛⠁⣠⡶⠋⠁⠀⠀⠀⠀
//⢻⡀⠀⠀⠀⠀⠘⢷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣀⣤⠶⠻⢦⣤⠟⣀⣤⠞⢋⣠⡴⠛⠁⠀⠀⠀⠀⠀⠀⠀
//⠈⢿⣄⠀⠀⠀⠀⠀⠈⠛⠳⠶⠤⠤⠤⠤⠤⠴⠶⠒⠛⠉⠁⠀⠀⢀⣠⡴⣞⣋⣤⠶⠋⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//⠀⠀⠙⢷⡶⠛⠳⣦⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣀⣤⣴⣾⠿⠿⠛⠋⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//⠀⠀⠀⠘⣧⡀⣀⣿⠦⣤⣤⣤⣤⣤⣤⠤⠶⠶⠞⠛⠋⠉⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//⠀⠀⠀⠀⠈⠉⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀