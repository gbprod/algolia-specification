<?php

namespace Tests\GBProd\AlgoliaSpecification;

use GBProd\AlgoliaSpecification\Handler;
use GBProd\AlgoliaSpecification\QueryFactory\AndXFactory;
use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\AlgoliaSpecification\QueryFactory\NotFactory;
use GBProd\AlgoliaSpecification\QueryFactory\OrXFactory;
use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Handler
     */
    private $handler;

    protected function setUp()
    {
        $this->registry = new Registry();
        $this->handler = new Handler($this->registry);
    }

    public function testConstructWillRegisterBaseFactoriess()
    {
        $spec1 = $this->createMock(Specification::class);
        $spec2 = $this->createMock(Specification::class);

        $this->assertInstanceOf(
            AndXFactory::class,
            $this->registry->getFactory(new AndX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            OrXFactory::class,
            $this->registry->getFactory(new OrX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            NotFactory::class,
            $this->registry->getFactory(new Not($spec1))
        );
    }

    public function testRegisterFactoryAddFactoryInRegistry()
    {
        $factory = $this->createMock(Factory::class);
        $spec = $this->createMock(Specification::class);

        $this->handler->registerFactory(get_class($spec), $factory);

        $this->assertEquals(
            $factory,
            $this->registry->getFactory($spec)
        );
    }

    public function testHandle()
    {
        $this->handler = new Handler(new Registry());

        $factory = $this->prophesize(Factory::class);

        $spec = $this->createMock(Specification::class);
        $this->handler->registerFactory(get_class($spec), $factory->reveal());

        $factory
            ->create($spec)
            ->willReturn('query')
            ->shouldBeCalled()
        ;

        $this->assertEquals('query', $this->handler->handle($spec));
    }
}
