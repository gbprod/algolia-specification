<?php

namespace GBProd\AlgoliaSpecification;

use GBProd\AlgoliaSpecification\QueryFactory\AndXFactory;
use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\AlgoliaSpecification\QueryFactory\NotFactory;
use GBProd\AlgoliaSpecification\QueryFactory\OrXFactory;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

/**
 * Handler for algolia specifications
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Handler
{
    /**
     * @param Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $this->registry->register(AndX::class, new AndXFactory($registry));
        $this->registry->register(OrX::class, new OrXFactory($registry));
        $this->registry->register(Not::class, new NotFactory($registry));
    }

    /**
     * handle specification
     *
     * @param Specification $spec
     *
     * @return string
     */
    public function handle(Specification $spec)
    {
        $factory = $this->registry->getFactory($spec);

        return $factory->create($spec);
    }

    /**
     * Register a factory for specification
     *
     * @param string  $classname specification fully qualified classname
     * @param Factory $factory
     */
    public function registerFactory($classname, Factory $factory)
    {
        $this->registry->register($classname, $factory);
    }
}
