<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class JobType.
 */
class JobType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $disabled = false;
        if (!$options['activate']) {
            $disabled = true;
        }

        $builder
            ->add('code', null, ['label' => 'jobs.label.code'])
            ->add('title', null, ['label' => 'jobs.label.title'])
            ->add('startTime', null, ['label' => 'jobs.label.start', 'minutes' => [0, 15, 30, 45]])
            ->add('endTime', null, ['label' => 'jobs.label.end', 'minutes' => [0, 15, 30, 45]])
            ->add('active', null, ['required' => false, 'label' => 'jobs.label.active', 'attr' => ['disabled' => $disabled]]);

        if ($options['submit']) {
            $builder->add('submit', SubmitType::class, ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
        }

        if ($options['delete']) {
            $builder->add('delete', SubmitType::class, ['label' => 'actions.delete', 'validation_groups' => false, 'attr' => ['class' => 'btn btn-danger']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['delete', 'submit', 'activate']);
        $resolver->setAllowedTypes('delete', 'boolean');
        $resolver->setAllowedTypes('submit', 'boolean');
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Entity\\Job',
            'delete' => false,
            'activate' => true,
            'submit' => true,
        ]);
        $resolver->setDefault('disabled', function (Options $options) {
            if (!$options['delete'] && !$options['submit']) {
                return true;
            }

            return false;
        });
    }
}
