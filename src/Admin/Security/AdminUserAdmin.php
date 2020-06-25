<?php

declare(strict_types=1);

namespace App\Admin\Security;

use DomainException;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Nucleos\UserBundle\Model\UserInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use App\Form\Admin\Type\CustomSecurityRolesType;
use Symfony\Component\Form\FormBuilderInterface;
use Nucleos\UserBundle\Model\LocaleAwareInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Nucleos\UserAdminBundle\Admin\Model\UserAdmin as BaseUserAdmin;

final class AdminUserAdmin extends BaseUserAdmin
{
    public $supportsPreviewMode = true;
    
    public function getNewInstance()
    {
        $instance = $this->userManager->createUser();

        // TODO: Find a better way to create editabe form models
        // BC layer
        try {
            $instance->getUsername();
        } catch (DomainException $exception) {
            $instance->setUsername('');
        }

        try {
            $instance->getEmail();
        } catch (DomainException $exception) {
            $instance->setEmail('');
        }

        return $instance;
    }

    public function getFormBuilder(): FormBuilderInterface
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options                      = $this->formOptions;
        $options['validation_groups'] = $this->isNewInstance() ? 'Registration' : 'Profile';

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    public function preUpdate($user): void
    {
        if (!$user instanceof UserInterface) {
            return;
        }

        $this->userManager->updateCanonicalFields($user);
        $this->userManager->updatePassword($user);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('groups')
            ->add('enabled', null, ['editable' => true])
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', [
                    'template' => '@NucleosUserAdmin/Admin/Field/impersonating.html.twig',
                ])
            ;
        }
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper): void
    {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('email')
        ;

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $filterMapper
                ->add('groups')
            ;
        }
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->with('form.tab_general')
                ->add('username')
                ->add('email')
            ->end()
        ;

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $showMapper
                ->with('form.tab_groups')
                    ->add('groups')
                ->end()
            ;
        }
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab('form.tab_user')
                ->with('form.group_general', ['class' => 'col-md-6'])->end()
                ->ifTrue($this->isLocaleAwareSubject())
                    ->with('form.group_locale', ['class' => 'col-md-6'])->end()
                ->ifEnd()
            ->end()
        ;

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('form.tab_security')
                    ->with('form.group_groups', ['class' => 'col-md-8'])->end()
                    ->with('form.group_status', ['class' => 'col-md-4'])->end()
                    ->with('form.group_roles', ['class' => 'col-md-12'])->end()
                ->end()
            ;
        }

        $formMapper
            ->tab('form.tab_user')
                ->with('form.group_general')
                    ->add('username')
                    ->add('email')
                    ->add('plainPassword', TextType::class, [
                        'required' => $this->isNewInstance(),
                    ])
                ->end()
                ->ifTrue($this->isLocaleAwareSubject())
                    ->with('form.group_locale')
                        ->add('locale', LocaleType::class, [
                            'required' => false,
                        ])
                        ->add('timezone', TimezoneType::class, [
                            'required'  => false,
                        ])
                    ->end()
                ->ifEnd()
            ->end()
        ;

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('form.tab_security')
                    ->with('form.group_status')
                        ->add('enabled', null, ['required' => false])
                    ->end()
                    ->with('form.group_groups')
                        ->add('groups', ModelType::class, [
                            'required' => false,
                            'expanded' => true,
                            'multiple' => true,
                        ])
                    ->end()
                    ->with('form.group_roles')
                        ->add('roles', CustomSecurityRolesType::class, [
                            'label'    => 'form.label_roles',
                            'expanded' => true,
                            'multiple' => true,
                            'required' => false,
                        ])
                    ->end()
                ->end()
            ;
        }
    }

    private function isNewInstance(): bool
    {
        return !$this->hasSubject() || null === $this->getSubject()|| null === $this->id($this->getSubject());
    }

    private function isLocaleAwareSubject(): bool
    {
        return is_subclass_of($this->getClass(), LocaleAwareInterface::class);
    }
}
