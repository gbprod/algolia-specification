<?php

declare(strict_types = 1);

namespace GBProd\AlgoliaSpecification\QueryFactory;

use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

/**
 * Factory for AndX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXFactory implements Factory
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {inheritdoc}
     */
    public function create(Specification $spec): string
    {
        if (!$spec instanceof AndX) {
            throw new \InvalidArgumentException();
        }

        $firstPartFactory  = $this->registry->getFactory($spec->getFirstPart());
        $secondPartFactory = $this->registry->getFactory($spec->getSecondPart());

        return sprintf(
            '(%s) AND (%s)',
            $firstPartFactory->create($spec->getFirstPart()),
            $secondPartFactory->create($spec->getSecondPart())
        );
    }
}
