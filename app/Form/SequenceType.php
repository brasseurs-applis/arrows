<?php

namespace BrasseursApplis\Arrows\App\Form;

use BrasseursApplis\Arrows\App\DTO\ScenarioDTO;
use BrasseursApplis\Arrows\App\DTO\SequenceDTO;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SequenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', ChoiceType::class, [
                'choices'  => [
                    'top' => Position::TOP,
                    'bottom' => Position::BOTTOM
                ]
            ])
            ->add('previewOrientation', ChoiceType::class, [
                'choices'  => [
                    'left' => Orientation::LEFT,
                    'right' => Orientation::RIGHT
                ]
            ])
            ->add('initiationOrientation', ChoiceType::class, [
                'choices'  => [
                    'left' => Orientation::LEFT,
                    'right' => Orientation::RIGHT
                ]
            ])
            ->add('mainOrientation', ChoiceType::class, [
                'choices'  => [
                    'left' => Orientation::LEFT,
                    'right' => Orientation::RIGHT
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( [ 'data_class' => SequenceDTO::class ] );
    }
}
