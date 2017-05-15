<?php

declare(strict_types = 1);

namespace TestingBundle\Service\Alice;

use Fidry\AliceDataFixtures\Loader\MultiPassLoader;
use Fidry\AliceDataFixtures\LoaderInterface;

class OrderMultiPassLoader implements LoaderInterface
{
    /** @var \Fidry\AliceDataFixtures\Loader\MultiPassLoader */
    private $multiPassLoader;

    /**
     * @param \Fidry\AliceDataFixtures\Loader\MultiPassLoader $loader
     */
    public function __construct(MultiPassLoader $loader)
    {
        $this->multiPassLoader = $loader;
    }

    /**
     * @var array
     */
    private static $order = [
        'MyBundle\Entity\Carrier' => 320,
        'MyBundle\Entity\Product' => 300,
        'MyBundle\Entity\ProductOption' => 200,
        'MyBundle\Entity\DeliverySlip' => 100,
        'MyBundle\Entity\TrackingCode' => 80,
        'MyBundle\Entity\ScaleUser' => 50,
    ];

    /**
     * {@inheritdoc}
     */
    public function load(array $fixturesFiles, array $parameters = [], array $objects = []): array
    {
        $objects = $this->multiPassLoader->load($fixturesFiles, $parameters, $objects);

        usort($objects, array($this, 'orderByPriority'));

        return $objects;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return int|mixed
     */
    public function orderByPriority($a, $b)
    {
        return $this->getPriority($b) - $this->getPriority($a);
    }

    /**
     * @param mixed $entityObject
     * @return int|mixed
     */
    private function getPriority($entityObject)
    {
        $className = get_class($entityObject);
        $priority = 1;
        if (!empty(self::$order[$className])) {
            $priority = self::$order[$className];
        }

        return $priority;
    }
}
