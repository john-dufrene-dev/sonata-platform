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

namespace App\Admin\Security;

use Sonata\Form\Type\DatePickerType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use App\Form\Admin\Type\CustomSecurityRolesType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

final class AdminUserAdmin extends BaseUserAdmin
{
    public $supportsPreviewMode = true;

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->with('General')
                ->add('username')
                ->add('email')
            ->end()
            ->with('Groups')
                ->add('groups')
            ->end()
            ->with('Profile')
                ->add('dateOfBirth')
                ->add('firstname')
                ->add('lastname')
                ->add('website')
                ->add('biography')
                ->add('gender')
                ->add('locale')
                ->add('timezone')
                ->add('phone')
            ->end()
            ->with('Social')
                // ->add('facebookUid')
                ->add('facebookName')
                // ->add('twitterUid')
                ->add('twitterName')
                // ->add('gplusUid')
                // ->add('gplusName')
            ->end()
        ;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $showMapper
                ->with('Security')
                    ->add('token')
                    ->add('twoStepVerificationCode')
                ->end()
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        // define group zoning
        $formMapper
            ->tab('User')
                ->with('Profile', ['class' => 'col-md-6'])->end()
                ->with('General', ['class' => 'col-md-6'])->end()
                ->with('Social', ['class' => 'col-md-6'])->end()
            ->end()
        ;

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('Security')
                    ->with('Status', ['class' => 'col-md-4'])->end()
                    ->with('Groups', ['class' => 'col-md-4'])->end()
                    ->with('Keys', ['class' => 'col-md-4'])->end()
                    ->with('Roles', ['class' => 'col-md-12'])->end()
                ->end()
            ;
        }

        $now = new \DateTime();

        $genderOptions = [
            'choices' => \call_user_func([$this->getUserManager()->getClass(), 'getGenderList']),
            'required' => true,
            'translation_domain' => $this->getTranslationDomain(),
        ];

        $formMapper
            ->tab('User')
                ->with('General')
                    ->add('username')
                    ->add('email')
                    ->add('plainPassword', TextType::class, [
                        'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
                    ])
                ->end()
                ->with('Profile')
                    ->add('dateOfBirth', DatePickerType::class, [
                        'years' => range(1900, $now->format('Y')),
                        'dp_min_date' => '1-1-1900',
                        'dp_max_date' => $now->format('c'),
                        'required' => false,
                    ])
                    ->add('firstname', null, ['required' => false])
                    ->add('lastname', null, ['required' => false])
                    ->add('website', UrlType::class, ['required' => false])
                    ->add('biography', TextType::class, ['required' => false])
                    ->add('gender', ChoiceType::class, $genderOptions)
                    ->add('locale', LocaleType::class, ['required' => false])
                    ->add('timezone', TimezoneType::class, ['required' => false])
                    ->add('phone', null, ['required' => false])
                ->end()
                ->with('Social')
                    // ->add('facebookUid', null, ['required' => false])
                    ->add('facebookName', null, ['required' => false])
                    // ->add('twitterUid', null, ['required' => false])
                    ->add('twitterName', null, ['required' => false])
                    // ->add('gplusUid', null, ['required' => false])
                    // ->add('gplusName', null, ['required' => false])
                ->end()
            ->end()
            ;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('Security')
                    ->with('Status')
                        ->add('enabled', null, ['required' => false])
                    ->end()
                    ->with('Groups')
                        ->add('groups', ModelType::class, [
                            'required' => false,
                            'expanded' => true,
                            'multiple' => true,
                        ])
                    ->end()
                    ->with('Roles')
                        ->add('realRoles', CustomSecurityRolesType::class, [
                            'label' => 'form.label_roles',
                            'expanded' => true,
                            'multiple' => true,
                            'required' => false,
                        ])
                    ->end()
                    ->with('Keys')
                        ->add('token', null, ['required' => false])
                        ->add('twoStepVerificationCode', null, ['required' => false])
                    ->end()
                ->end()
            ;
        }
    }
}
