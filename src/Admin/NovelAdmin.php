<?php

declare(strict_types=1);

namespace App\Admin;

use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class NovelAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('title')
            ->add('description')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('title', fieldDescriptionOptions: ['route' => ['name' => 'edit']])
            ->add('description')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->tab('General')->with('Main')
            ->add('title')
            ->add('description')
            ->end()->end();
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Edit Novel', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Manage Chapters', $admin->generateMenuUrl('admin.chapter.list', ['id' => $id]));
        }
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->tab('General')->with('Main')
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('chapters')
            ->end()->end();
    }
}
