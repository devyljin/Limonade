<?php

namespace Agrume\Limonade\DTO\Component\HttpFoundation;

use Agrume\Limonade\DTO\Component\AbstractComponentDTO;
use Symfony\Component\HttpFoundation\HeaderBag;

class HeaderBagDTO extends AbstractComponentDTO
{
    private array $header;
    protected array $mountableStdClass = [HeaderBag::class];
    public function __construct(HeaderBag $headerBag){
        $this->header = $headerBag->allPreserveCase();
    }

    public function getPayload(): mixed
    {
        return $this->header;
    }

}