<?php

namespace Tests\GBProd\AlgoliaSpecification;

use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\AlgoliaSpecification\QueryFactory\OrXFactory;
use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class OrXFactoryTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new OrXFactory(new Registry());

        $this->assertInstanceOf(OrXFactory::class, $factory);
    }

    public function testCreateReturnsAndxQuery()
    {
        $andx = $this->createOrX();
        $registry = $this->createRegistry($andx);

        $factory = new OrXFactory($registry);

        $query = $factory->create($andx);

        $this->assertEquals('(first_part) OR (second_part)', $query);
    }

    /**
     * @return OrX
     */
    private function createOrX()
    {
        return new OrX(
            $this->prophesize(Specification::class)->reveal(),
            $this->prophesize(Specification::class)->reveal()
        );
    }

    /**
     * @param OrX $andx
     *
     * @return Registry
     */
    private function createRegistry($andx)
    {
        $firstFactory = $this->createMock(Factory::class);
        $firstFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn('first_part')
        ;

        $secondFactory = $this->createMock(Factory::class);
        $secondFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn('second_part')
        ;

        $registry = new Registry();

        $registry->register(get_class($andx->getFirstPart()), $firstFactory);
        $registry->register(get_class($andx->getSecondPart()), $secondFactory);

        return $registry;
    }


    public function testCreateThrowExceptionIfNotOrXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new OrXFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec);
    }
}
