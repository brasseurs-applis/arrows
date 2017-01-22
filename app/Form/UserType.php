<?php

namespace BrasseursApplis\Arrows\App\Form;

use BrasseursApplis\Arrows\App\DTO\UserDTO;
use BrasseursApplis\Arrows\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class)
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => User::ROLE_ADMIN,
                    'Researcher' => User::ROLE_RESEARCHER,
                    'Subject' => User::ROLE_SUBJECT
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->add('save', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( [ 'data_class' => UserDTO::class ] );
    }
}
