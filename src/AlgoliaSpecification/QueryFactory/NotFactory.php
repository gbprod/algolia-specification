<?php

declare(strict_types = 1);

namespace GBProd\AlgoliaSpecification\QueryFactory;

use GBProd\AlgoliaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

/**
 * Factory for Not specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class NotFactory implements Factory
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
        if (!$spec instanceof Not) {
            throw new \InvalidArgumentException();
        }

        $wrappedSpec = $spec->getWrappedSpecification();
        $this->validate($wrappedSpec);

        $firstPartFactory = $this->registry->getFactory($wrappedSpec);

        return sprintf(
            'NOT %s',
            $firstPartFactory->create($wrappedSpec)
        );
    }

    /**
     * Algolia does not allows to negate a group
     * @see https://www.algolia.com/doc/api-client/php/parameters#filters
     *
     * @throws \InvalidArgumentException
     */
    private function validate(Specification $spec)
    {
        if ($spec instanceof Not || $spec instanceof AndX || $spec instanceof OrX) {
            throw new \InvalidArgumentException('Algolia does not allows to negate a group');
        }
    }
}
