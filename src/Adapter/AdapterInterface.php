<?php
namespace Agrume\Limonade\Adapter;

interface AdapterInterface
{
    /**
     * Get the good class
     */
    public function adaptee(): Object;

}