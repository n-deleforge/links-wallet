<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class ReadmePersonalizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isVisible', CheckboxType::class, [
                'required' => false,
                'help' => new TranslatableMessage('settings.readme.personalization.visibility.help')
            ])
            ->add('avatar', FileType::class, [
                'help' => new TranslatableMessage('settings.readme.personalization.avatar.help'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG/PNG image.',
                    ])
                ]
            ])
            ->add('bio', TextareaType::class, [
                'help' => new TranslatableMessage('settings.readme.personalization.bio.help'),
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 255
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
