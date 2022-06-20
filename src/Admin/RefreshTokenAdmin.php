<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class RefreshTokenAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('clientIp')
            ->add('expiresAt')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('clientIp')
            ->add('createdAt')
            ->add('expiresAt')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        if (!$this->isChild()) {
            $collection->clear();
        }
        $collection->remove('export');
        $collection->remove('show');
        $collection->remove('edit');
        $collection->remove('create');
    }
}
