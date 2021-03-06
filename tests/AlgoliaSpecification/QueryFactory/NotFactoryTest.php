<?php

namespace AlgoliaSpecification\QueryFactory;

use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\AlgoliaSpecification\QueryFactory\NotFactory;
use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class NotFactoryTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new NotFactory(new Registry());

        $this->assertInstanceOf(NotFactory::class, $factory);
    }

    public function testCreateReturnsNotQuery()
    {
        $not = $this->createNot();
        $registry = $this->createRegistry($not);

        $factory = new NotFactory($registry);

        $query = $factory->create($not);

        $this->assertEquals('NOT query', $query);
    }

    /**
     * @return Not
     */
    private function createNot()
    {
        return new Not(
            $this->createMock(Specification::class)
        );
    }

    /**
     * @param Not $not
     *
     * @return Registry
     */
    private function createRegistry($not)
    {
        $factory = $this->createMock(Factory::class);
        $factory
            ->expects($this->any())
            ->method('create')
            ->willReturn('query')
        ;

        $registry = new Registry();

        $registry->register(get_class($not->getWrappedSpecification()), $factory);

        return $registry;
    }

    public function testCreateThrowExceptionIfNotNotSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new NotFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec);
    }

    public function testCreateThrowExceptionIfWrappedSpecificationIsAGroup()
    {
        $spec = new Not(
            new OrX(
                $this->createMock(Specification::class),
                $this->createMock(Specification::class)
            )
        );

        $registry = new Registry();
        $factory = new NotFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec);
    }
}
