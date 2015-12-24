<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GoogleConnectionType.
 */
class GoogleConnectionType extends AbstractType
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
            ->add('title', null, ['label' => 'google_connections.label.title'])
            ->add('clientId', null, ['label' => 'google_connections.label.client_id'])
            ->add('clientSecret', null, ['label' => 'google_connections.label.client_secret'])
            ->add('projectId', null, ['label' => 'google_connections.label.project_id'])
            ->add('internalId', null, ['label' => 'google_connections.label.internal_id'])
            ->add('jobDayComplete', null, ['required' => false, 'label' => 'google_connections.label.job_day_complete'])
            ->add('nurseryDayComplete', null, ['required' => false, 'label' => 'google_connections.label.nursery_day_complete'])
            ->add('active', null, ['required' => false, 'label' => 'calendars.label.active', 'attr' => ['disabled' => $disabled]]);

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
            'data_class' => 'AppBundle\\Entity\\GoogleConnection',
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
