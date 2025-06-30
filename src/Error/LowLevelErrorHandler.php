<?php
namespace Agrume\Limonade\Error;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LowLevelErrorHandler
{
    private bool $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function __invoke(\Throwable $e): void
    {
        $flatten = FlattenException::createFromThrowable($e);
        $statusCode = $flatten->getStatusCode();

        $data = [
        'status' => 'error',
        'code' => $statusCode,
        'message' => $this->debug
        ? $e->getMessage()
        : Response::$statusTexts[$statusCode] ?? 'Internal Server Error',
        ];

        if ($this->debug) {
            $data['exception'] = [
                'type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        }

        $response = new JsonResponse(["nativeException" => $data], 200);
        $response->send();
    }
}