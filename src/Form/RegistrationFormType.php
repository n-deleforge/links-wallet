<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'help' => new TranslatableMessage('register.email.help'),
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('name', TextType::class, [
                'help' => new TranslatableMessage('register.name.help'),
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4
                    ]),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => "The password fields must match.",
                    'required' => true,
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank(),
                        new Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[!@#\$%\^&\*+])[a-zA-Z\d!@#\$%\^&\*+]{6,4096}$/')
                    ],
                    'first_name' => 'password',
                    'second_name' => 'confirm',
                    'first_options' => [
                        'help' => new TranslatableMessage('register.password.help')
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
