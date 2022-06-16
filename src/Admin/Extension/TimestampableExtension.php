<?php

declare(strict_types=1);

namespace App\Admin\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Security\Core\Security;

class TimestampableExtension extends AbstractAdminExtension
{
    public function __construct(private readonly Security $security)
    {
    }

    public function configureFormFields(FormMapper $form): void
    {
        if (!$this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return;
        }

        if ($form->getAdmin()->isCurrentRoute('create') || $form->getAdmin()->hasParentFieldDescription()) {
            return;
        }

        $form
            ->tab('Metadata')
            ->with('Date records')
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'disabled' => true,
            ])
            ->add('updatedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'disabled' => true,
            ])
            ->end()
            ->end()
        ;
    }

    public function configureShowFields(ShowMapper $show): void
    {
        $show
            ->tab('Metadata')
            ->with('Date records')
            ->add('createdAt')
            ->add('updatedAt')
            ->end()
            ->end()
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('createdAt', null, [
                'field_options' => [
                    'widget' => 'single_text',
                ],
            ])
            ->add('updatedAt', null, [
                'field_options' => [
                    'widget' => 'single_text',
                ],
            ])
        ;
    }
}
