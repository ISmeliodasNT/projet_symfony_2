<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\UserRole;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
            'label' => 'Nom',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => UserRole::USER->value,
                    'Manager' => UserRole::MANAGER->value,
                    'Administrateur' => UserRole::ADMIN->value,
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
            ])
            ->add('email')
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user || null === $user->getId()) {
                $form->add('plainPassword', PasswordType::class, [
                    'label' => 'Mot de passe',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                        new Length(['min' => 6, 'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères']),
                    ],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
