<?php

namespace BrasseursApplis\Arrows\App\Form;

use BrasseursApplis\Arrows\App\DTO\ScenarioDTO;
use BrasseursApplis\Arrows\App\DTO\SessionDTO;
use BrasseursApplis\Arrows\App\DTO\UserDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('researcher', EntityType::class, [
                'class' => UserDTO::class,
                'choice_label' => 'userName'
            ])
            ->add('subjectOne', EntityType::class, [
                'class' => UserDTO::class,
                'choice_label' => 'userName'
            ])
            ->add('subjectTwo', EntityType::class, [
                'class' => UserDTO::class,
                'choice_label' => 'userName'
            ])
            ->add('scenario', EntityType::class, [
                'class' => ScenarioDTO::class,
                'choice_label' => 'name'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( [ 'data_class' => SessionDTO::class ] );
    }
}
