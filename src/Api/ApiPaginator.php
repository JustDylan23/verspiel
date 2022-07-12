<?php

declare(strict_types=1);

namespace App\Api;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiPaginator
{
    public const PAGE_SIZE = 10;

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function paginate(QueryBuilder $queryBuilder, $pageSize = self::PAGE_SIZE): array
    {
        $page = $this->requestStack->getCurrentRequest()->query->getInt('page', 1);

        if ($page < 1) {
            $page = 1;
        }

        $queryBuilder
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize) // set the limit
        ;

        $paginator = new Paginator($queryBuilder, fetchJoinCollection: true);

        return [
            'items' => $paginator->getIterator()->getArrayCopy(),
            'rows' => count($paginator),
            'perPage' => $pageSize,
        ];
    }

    public function cursorPaginate(
        QueryBuilder $queryBuilder,
        ?int $cursor,
        string $order,
        $pageSize = self::PAGE_SIZE
    ): array {
        $queryBuilder
            ->orderBy($queryBuilder->getRootAliases()[0].'.id', $order)
            ->setMaxResults($pageSize)
        ;
        if ($cursor) {
            $queryBuilder
                ->andWhere($queryBuilder->getRootAliases()[0].'.id '.('ASC' === $order ? '>' : '<').' :id')
                ->setParameter('id', $cursor)
            ;
        }
        $items = $queryBuilder->getQuery()->getResult();
        if (\count($items) > 0 && is_array($items[0])) {
            foreach ($items as &$item) {
                $obj = $item[0];
                unset($item[0]);
                foreach ($item as $extraFieldKey => $extraFieldValue) {
                    $obj->{$extraFieldKey} = $extraFieldValue;
                }
                $item = $obj;
            }
        }

        return [
            'items' => $items,
            'cursor' => self::PAGE_SIZE === \count($items) ? end($items)->getId() : null,
        ];
    }
}
