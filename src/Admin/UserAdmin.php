<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class UserAdmin extends AbstractAdmin
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('username')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('username', fieldDescriptionOptions: ['route' => ['name' => 'edit']])
            ->add('email')
            ->add('isPrivileged', 'boolean')
            ->add('verified')
            ->add('disabled')
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
        $isCreate = $this->isCurrentRoute('create');
        $constraints = [];
        if ($isCreate) {
            $constraints[] = new NotBlank();
        }
        $form
            ->tab('General')->with('Main')
            ->add('username')
            ->add('email')
            ->add('verified', options: ['label' => 'Email verified'])
            ->add('disabled', options: ['label' => 'Disable account'])
            ->add('roles', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Admin' => 'ROLE_SUPER_ADMIN',
                    'Editor' => 'ROLE_EDITOR',
                    'Comment moderator' => 'ROLE_MODERATOR',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => $isCreate,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => $constraints,
            ])
            ->end()->end();
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->tab('General')
            ->with('Main')
            ->add('id')
            ->add('username')
            ->add('email')
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
            $menu->addChild('Edit User', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Manage Tokens', $admin->generateMenuUrl('admin.refresh_token.list', ['id' => $id]));
        }
    }

    protected function preUpdate(object $object): void
    {
        $this->encodePlainPassword($object);
    }

    protected function prePersist(object $object): void
    {
        $this->encodePlainPassword($object);
    }

    private function encodePlainPassword(User $user): void
    {
        if ($user->plainPassword) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->plainPassword));
        }
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('export');
    }
}
