<?php

namespace Tests\GBProd\AlgoliaSpecification;

use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\Specification;

/**
 * Tests for registry
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class RegistryTest extends \PHPUnit_Framework_TestCase implements Specification
{
    public function testConstruct()
    {
        $registry = new Registry();

        $this->assertInstanceOf(Registry::class, $registry);
    }

    public function isSatisfiedBy($candidate)
    {
        return true;
    }

    public function testgetFactoryThrowsOutOfRangeExceptionIfFactoryNotRegistred()
    {
        $registry = new Registry();

        $this->expectException(\OutOfRangeException::class);

        $registry->getFactory($this);
    }

    public function testgetFactoryReturnsAssociatedFactory()
    {
        $registry = new Registry();

        $factory = $this->prophesize(Factory::class)->reveal();

        $registry->register(self::class, $factory);

        $this->assertEquals(
            $factory,
            $registry->getFactory($this)
        );
    }
}
