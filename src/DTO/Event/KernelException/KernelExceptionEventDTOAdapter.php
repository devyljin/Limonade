<?php

namespace Agrume\Limonade\DTO\Event\KernelException;

use Agrume\Limonade\DTO\AbstractDTOAdapter;
use Symfony\Component\Config\Definition\Exception\Exception;

//use Agrume\Limonade\DTO\Event\KernelException\Generated\CacheGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\ConsoleGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\CssSelectorGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\DebugGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\ErrorHandlerGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\FilesystemGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\HttpKernelGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\LocaleGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\MailerGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\MessengerGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\NativeGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\RoutingGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\SecurityGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\TranslationGeneratedAdapterTrait;
//use Agrume\Limonade\DTO\Event\KernelException\Generated\ValidatorGeneratedAdapterTrait;

class KernelExceptionEventDTOAdapter extends AbstractDTOAdapter
{
//    use CacheGeneratedAdapterTrait;
//    use ConsoleGeneratedAdapterTrait;
//    use CssSelectorGeneratedAdapterTrait;
//    use DebugGeneratedAdapterTrait;
//    use ErrorHandlerGeneratedAdapterTrait;
//    use FilesystemGeneratedAdapterTrait;
//    use HttpKernelGeneratedAdapterTrait;
//    use LocaleGeneratedAdapterTrait;
//    use MailerGeneratedAdapterTrait;
//    use MessengerGeneratedAdapterTrait;
//    use NativeGeneratedAdapterTrait;
//    use RoutingGeneratedAdapterTrait;
//    use SecurityGeneratedAdapterTrait;
//    use TranslationGeneratedAdapterTrait;
//    use ValidatorGeneratedAdapterTrait;


    protected $throwable;

    public function __construct($throwable){

        $this->throwable = $throwable;
    }

    /**
     * @inheritDoc
     */
    public function adaptee(): Object
    {
        $classShortname = (new \ReflectionClass($this->throwable))->getShortName();

        $this->setShortName($classShortname);
        $contextArray =   explode("\\", $this->throwable::class);
        if(count($contextArray) >2) {
            $context = $contextArray[2];
            if($context === "Config"){
                $context = "Native";
            }
        } else {
            $context = "Native";
        }

        $methodName ='adaptee' . $context . $this->getShortName();

        if(method_exists(self::class, $methodName)){
            return $this->$methodName($this->throwable);
        }
        //        return new JsonResponse(["exception" => $this->throwable->getMessage()], 200, [], true);
        throw new Exception("No adapter method found for {$this->getShortName()} Method Should be {$methodName} with context : {$context}");
    }

    private function defaultExceptionMount($dto,$throwable){
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable->getPrevious()
        ];
        $dto->setPayload($payload);
        return $dto;
    }
        private function httpExceptionMount($dto,  $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getStatusCode(),
            $throwable->getMessage(),
            $throwable->getPrevious(),
            $throwable->getHeaders(),
            $throwable->getCode()
        ];
        $dto->setPayload($payload);
        return $dto;
    }

    private function debugFatalExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString(),
        ];
        $dto->setPayload($payload);
        return $dto;
    }
    private function securityAuthExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getCode(),
            method_exists($throwable, 'getToken') ? $throwable->getToken() : null,
            $throwable->getPrevious(),
        ];
        $dto->setPayload($payload);
        return $dto;
    }
    private function validationErrorExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable->getPrevious(),
        ];
        $dto->setPayload($payload);
        return $dto;
    }
    private function ioExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable->getPath(),
            $throwable->getPrevious()
        ];
        $dto->setPayload($payload);
        return $dto;
    }
    private function messengerExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            array_map(fn($ex) => $ex->getMessage(), $throwable->getNestedExceptions()),
            $throwable->getPrevious()
        ];
        $dto->setPayload($payload);
        return $dto;
    }
    private function routingExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable->getPrevious()
        ];
        $dto->setPayload($payload);
        return $dto;
    }
    private function translationExceptionMount($dto, $throwable)
    {
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable->getPrevious()
        ];
        $dto->setPayload($payload);
        return $dto;
    }

    private function errorHandlerExceptionMount($dto, $throwable){
        $dto->setShortName($this->getShortName());
        $dto->setMountableStdClass([$throwable::class]);
        $payload = [
            $throwable->getMessage(),
            $throwable->getPrevious(),
        ];
        $dto->setPayload($payload);
        return $dto;
    }
}