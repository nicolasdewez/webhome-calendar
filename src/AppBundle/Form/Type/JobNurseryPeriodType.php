<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class JobNurseryPeriodType.
 */
class JobNurseryPeriodType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startTime', null, ['input'  => 'datetime', 'minutes' => [0, 15, 30, 45], 'label' => 'job_nurseries.label.start'])
            ->add('endTime', null, ['input'  => 'datetime', 'minutes' => [0, 15, 30, 45], 'label' => 'job_nurseries.label.end'])
            ->add('type', ChoiceType::class, [
                'label' => 'job_nurseries.label.type',
                'choices' => [
                    'job_nurseries.type.same_day' => 1,
                    'job_nurseries.type.next_day' => 2,
                    'job_nurseries.type.serial_day' => 3,
                    'job_nurseries.type.serial_during' => 4,
                    'job_nurseries.type.serial_after' => 5,
                ],
                'choices_as_values' => true,
            ])
            ->add('meal', null, ['required' => false, 'label' => 'job_nurseries.label.meal']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Entity\\JobNurseryPeriod',
        ]);
    }
}
