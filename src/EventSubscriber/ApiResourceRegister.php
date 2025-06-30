<?php
namespace Agrume\Limonade\EventSubscriber;

use Agrume\Limonade\Annotation\ApiResource;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class  ApiResourceRegister implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function onKernelController(ControllerEvent $event)
    {

        $controller = $event->getController();

        if (is_array($controller)) {
            $reflection = new \ReflectionMethod($controller[0], $controller[1]);
            $attributes = $reflection->getAttributes(ApiResource::class);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
        'kernel.controller' => 'onKernelController',
        ];
    }
}
