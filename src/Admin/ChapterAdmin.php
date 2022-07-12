<?php

declare(strict_types=1);

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

final class ChapterAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('number')
            ->add('title')
        ;
        if (!$this->isChild()) {
            $filter->add('novel');
        }
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('title', fieldDescriptionOptions: ['route' => ['name' => 'edit']])
            ->add('number')
            ->add('published')
        ;

        if (!$this->isChild()) {
            $list->add('novel', fieldDescriptionOptions: ['route' => ['name' => 'edit']]);
        } else {
            $list
                ->add('createdAt')
                ->add('updatedAt')
            ;
        }
        $list->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
            ],
        ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->tab('General')->with('Main')
            ->add('number')
            ->add('title')
            ->add('published')
            ->add('content', CKEditorType::class, [
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

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->tab('General')
            ->with('Main')
            ->add('id')
            ->add('number')
            ->add('title')
            ->add('content')
            ->end()->end();
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('export');
    }
}
