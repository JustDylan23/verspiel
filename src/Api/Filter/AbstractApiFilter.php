<?php

declare(strict_types=1);

namespace App\Api\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractApiFilter
{
    protected array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['param', 'field']);
        $resolver->setAllowedTypes('param', 'string');
        $resolver->setAllowedTypes('field', 'string');
    }

    public function getValue(Request $request)
    {
        $value = $request->query->get($this->options['param']);

        return empty($value) ? null : $value;
    }

    abstract public function apply(QueryBuilder $queryBuilder, mixed $value, string $uniqueParameterAlias): void;
}
