<?php

namespace App\Form;

use App\Entity\LinkModel;
use App\Entity\LinkUser;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReadmeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'help' => new TranslatableMessage('readme.form.usernameHelp'),
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3
                    ])
                ]
            ])
            ->add('model', EntityType::class, [
                'class' => LinkModel::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'help' => new TranslatableMessage('readme.form.modelHelp'),
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LinkUser::class,
        ]);
    }
}
