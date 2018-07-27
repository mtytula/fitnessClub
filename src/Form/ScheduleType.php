<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ScheduleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'html5' => false,
                'widget' => 'single_text'
            ])
            ->add('endDate', DateTimeType::class, [
                'html5' => false,
                'widget' => 'single_text'
            ])
            ->add('coach', ChoiceType::class, [
                'choices' => $options['coaches'],
                'choice_label' => function ($choiceValue) {
                    return $choiceValue->getFirstName() . " " . $choiceValue->getLastName();
                },
            ])
            ->add('activity', ChoiceType::class, [
                'choices' => $options['activities'],
                'choice_label' => function ($choiceValue) {
                    return $choiceValue->getName();
                }
            ])
            ->add('room', ChoiceType::class, [
                'choices' => $options['rooms'],
                'choice_label' => function ($choiceValue) {
                    return $choiceValue->getName();
                }
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'coaches' => null,
            'activities' => null,
            'rooms' => null
        ));
    }
}
