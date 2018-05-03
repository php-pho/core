<?php
namespace Pho\Core;

use DI\Container;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

class Application
{
    private $containerBuilder;
    private $container;

    public function __construct(ContainerBuilder $containerBuilder = null)
    {
        $this->containerBuilder = $containerBuilder;
    }

    public function register(ServiceProviderInterface $service_provider) : self {
        $service_provider->register($this->containerBuilder);

        return $this;
    }

    public function buildContainer() : Container {
        $this->container = $this->containerBuilder->build();
        $this->container->set(ContainerInterface::class, $this->container);
        $this->container->set(Container::class, $this->container);
        $this->container->set(static::class, $this);

        return $this->container;
    }

    public function getContainer() : Container {
        return $this->container;
    }

    public function run() {
        $args = func_get_args();
        $program = array_shift($args);

        if (!method_exists($program, 'run')) {
            throw new \RuntimeException(sprintf("Program '%' doesn't have 'run' method.", $program));
        }

        return $this->buildContainer()->call([$program, 'run'], $args);
    }
}
