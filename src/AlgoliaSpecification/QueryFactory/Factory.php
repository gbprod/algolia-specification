<?php

namespace GBProd\AlgoliaSpecification\QueryFactory;

use GBProd\Specification\Specification;

/**
 * Interface for Algolia query factory
 *
 * @author gbprod <contact@gb-prod.fr>
 */
interface Factory
{
    /**
     * Create query for specification
     *
     * @param Specification $spec
     *
     * @return string
     */
    public function create(Specification $spec);
}
