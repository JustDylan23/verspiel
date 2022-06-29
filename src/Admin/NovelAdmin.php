<?php

declare(strict_types=1);

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

final class NovelAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('title')
            ->add('featured')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('title', fieldDescriptionOptions: ['route' => ['name' => 'edit']])
            ->add('featured')
            ->add('shortDescription')
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
            ->add('featured', null, [
                'label' => 'Featured on homepage',
            ])
            ->add('shortDescription', options: [
                'help' => 'Short description is shown in the list of novels and when searching for novels.',
            ])
            ->add('description', CKEditorType::class, [
                'config' => [
                    'extraPlugins' => ['autogrow', 'image2'],
                ],
                'plugins' => [
                    'autogrow' => [
                        'path' => '/ckeditor/plugins/autogrow/', // with trailing slash
                        'filename' => 'plugin.js',
                    ],
                    'image2' => [
                        'path' => '/ckeditor/plugins/image2/', // with trailing slash
                        'filename' => 'plugin.js',
                    ],
                ],
            ])
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

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('export');
    }
}
