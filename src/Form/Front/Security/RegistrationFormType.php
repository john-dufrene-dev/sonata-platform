<?php

namespace App\Form\Front\Security;

use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'register.label.email',
                'translation_domain' => 'validators',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'register.label.agrees.terms',
                'translation_domain' => 'validators',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'register.agree.terms',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                        'label' => 'register.label.password',
                        'translation_domain' => 'validators',
                ],
                'second_options' => [
                    'label' => 'register.label.repeatpassword',
                    'translation_domain' => 'validators',
                ],
                'options' => ['attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'Password',
                ]],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.registery.password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'register.min.password',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        'maxMessage' => 'register.max.password',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
