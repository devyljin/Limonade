<?php
namespace Agrume\Limonade\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class LimonadeBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // Ici tu peux charger ta configuration et services
    }
}
