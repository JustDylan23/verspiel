<?php

declare(strict_types=1);

namespace App\Api\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StringFilter extends AbstractApiFilter
{
    protected array $options;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('type', 'contains');
        $resolver->setAllowedValues('type', ['exact', 'contains']);
        parent::configureOptions($resolver);
    }

    public function getValue(Request $request)
    {
        $value = $request->query->get($this->options['param']);

        return empty($value) ? null : $value;
    }

    function apply(QueryBuilder $queryBuilder, mixed $value, string $uniqueParameterAlias): void
    {
        if ('contains' === $this->options['type']) {
            $queryBuilder
                ->andWhere("{$this->options['field']} LIKE :{$uniqueParameterAlias}")
                ->setParameter($uniqueParameterAlias, '%'.addcslashes($value, '%_').'%')
            ;
        } elseif ('exact' === $this->options['type']) {
            $queryBuilder
                ->andWhere("{$this->options['field']} = :{$uniqueParameterAlias}")
                ->setParameter($uniqueParameterAlias, $value)
            ;
        }
    }
}
