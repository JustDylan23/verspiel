<?php

declare(strict_types=1);

namespace App\Api\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiFilterProcessor
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * @param AbstractApiFilter[] $filters
     */
    public function apply(QueryBuilder $queryBuilder, array $filters): void
    {
        $request = $this->requestStack->getCurrentRequest();
        foreach ($filters as $key => $filter) {
            $value = $filter->getValue($request);
            if (null !== $value) {
                $filter->apply($queryBuilder, $value, 'param'.$key);
            }
        }
    }
}
