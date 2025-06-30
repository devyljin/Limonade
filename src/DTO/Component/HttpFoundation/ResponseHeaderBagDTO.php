<?php

namespace Agrume\Limonade\DTO\Component\HttpFoundation;

use Agrume\Limonade\DTO\Component\HttpFoundation\HeaderBagDTO;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ResponseHeaderBagDTO extends HeaderBagDTO
{
    protected array $mountableStdClass = [ResponseHeaderBag::class];


}