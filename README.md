# Algolia specification

[![Build Status](https://travis-ci.org/gbprod/algolia-specification.svg?branch=master)](https://travis-ci.org/gbprod/algolia-specification)
[![codecov](https://codecov.io/gh/gbprod/algolia-specification/branch/master/graph/badge.svg)](https://codecov.io/gh/gbprod/algolia-specification)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gbprod/algolia-specification/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gbprod/algolia-specification/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/574a9bc8ce8d0e004130d330/badge.svg)](https://www.versioneye.com/user/projects/574a9bc8ce8d0e004130d330)

[![Latest Stable Version](https://poser.pugx.org/gbprod/algolia-specification/version)](https://packagist.org/packages/gbprod/algolia-specification)
[![Total Downloads](https://poser.pugx.org/gbprod/algolia-specification/downloads)](https://packagist.org/packages/gbprod/algolia-specification)
[![Latest Unstable Version](https://poser.pugx.org/gbprod/algolia-specification/v/unstable)](https://packagist.org/packages/gbprod/algolia-specification)
[![License](https://poser.pugx.org/gbprod/algolia-specification/license)](https://packagist.org/packages/gbprod/algolia-specification)

This library allows you to create [Algolia](https://www.algolia.com/) queries using the [specification pattern](http://en.wikipedia.org/wiki/Specification_pattern).

## Usage

You can write specifications using [gbprod/specification](https://github.com/gbprod/specification) library.

### Creates a algolia specification filter

@see [Algolia documentation](https://www.algolia.com/doc/guides/search/filtering-faceting#filtering)

```php
namespace GBProd\Acme\Algolia\SpecificationFactory;

use GBProd\AlgoliaSpecification\QueryFactory\Factory;
use GBProd\Specification\Specification;

class IsAvailableFactory implements Factory
{
    public function create(Specification $spec)
    {
        return 'available=1';
    }
}
```

### Configure

```php
$registry = new GBProd\AlgoliaSpecification\Registry();

$handler = new GBProd\AlgoliaSpecification\Handler($registry);
$handler->registerFactory(IsAvailable::class, new IsAvailableFactory());
$handler->registerFactory(StockGreaterThan::class, new StockGreaterThanFactory());
```

### Use it

```php
$available = new IsAvailable();
$hightStock = new StockGreaterThan(4);

$availableWithLowStock = $available
    ->andX(
        $hightStock->not()
    )
;
$client = new \AlgoliaSearch\Client('YourApplicationID', 'YourAPIKey');
$index = $client->initIndex('index_name');

$query = $handler->handle($availableWithLowStock)

$results = $type->search(['filters' => $query]);
```

## Requirements

 * PHP 5.6+

## Installation

### Using composer

```bash
composer require gbprod/algolia-specification
```
