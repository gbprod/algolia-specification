<?php

namespace Tests\GBProd\AlgoliaSpecification;

use GBProd\AlgoliaSpecification\QueryFactory\AndXFactory;
use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

class AndXFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $factory = new AndXFactory(new Registry());

        $this->assertInstanceOf(AndXFactory::class, $factory);
    }

    public function testCreateReturnsAndxQuery()
    {
        $andx = $this->createAndX();
        $registry = $this->createRegistry($andx);

        $factory = new AndXFactory($registry);

        $query = $factory->create($andx);

        $this->assertEquals('(first_part) AND (second_part)', $query);
    }

    /**
     * @return AndX
     */
    private function createAndX()
    {
        return new AndX(
            $this->prophesize(Specification::class)->reveal(),
            $this->prophesize(Specification::class)->reveal()
        );
    }

    /**
     * @param AndX $andx
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


    public function testCreateThrowExceptionIfNotAndXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new AndXFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec);
    }
}
