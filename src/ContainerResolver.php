<?php
namespace Pho\Core;

use Psr\Container\ContainerInterface;

class ContainerResolver
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (!$this->container->has($value)) {
            throw new \RuntimeException("Unable to resolve component name: {$value}");
        }

        return $this->container->get($value);
    }
}
