<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Admin\UserAdmin;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use App\Form\Admin\Type\CustomSecurityRolesType;
use Sonata\UserBundle\Admin\Model\GroupAdmin as BaseGroupAdmin;

final class AdminGroup extends BaseGroupAdmin
{
    public $supportsPreviewMode = true;

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('name')
            ->add('roles', null, [
                'header_class' => 'col-md-6',
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => [
                        'template' => 'admin/SonataAdminBundle/CRUD/list__action_show.html.twig',
                    ],
                    'edit' => [
                        'template' => 'admin/SonataAdminBundle/CRUD/list__action_edit.html.twig',
                    ],
                    'delete' => [
                        'template' => 'admin/SonataAdminBundle/CRUD/list__action_delete.html.twig',
                    ],
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab('Group')
                ->with('General', ['class' => 'col-md-6'])
                    ->add('name')
                ->end()
            ->end()
            ->tab('Security')
                ->with('Roles', ['class' => 'col-md-12'])
                    ->add('roles', CustomSecurityRolesType::class, [
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                    ])
                ->end()
            ->end()
        ;
    }
}
