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
        parent::configureOptions($resolver);
        $resolver->setDefault('type', 'contains');
        $resolver->setAllowedValues('type', ['exact', 'contains']);
        $resolver->setAllowedTypes('field', ['string', 'array']);
    }

    public function getValue(Request $request)
    {
        $value = $request->query->get($this->options['param']);

        return empty($value) ? null : $value;
    }

    public function apply(QueryBuilder $queryBuilder, mixed $value, string $uniqueParameterAlias): void
    {
        $operator = 'exact' === $this->options['type'] ? '=' : 'LIKE';
        if (is_scalar($this->options['field'])) {
            $queryBuilder->andWhere("{$this->options['field']} {$operator} :{$uniqueParameterAlias}");
        } elseif (is_array($this->options['field'])) {
            $queryBuilder->andWhere(implode(' OR ', array_map(
                fn ($field) => "{$field} {$operator} :{$uniqueParameterAlias}",
                $this->options['field']
            )));
        }
        $queryBuilder
            ->setParameter($uniqueParameterAlias, '%'.addcslashes($value, '%_').'%')
        ;
    }
}
