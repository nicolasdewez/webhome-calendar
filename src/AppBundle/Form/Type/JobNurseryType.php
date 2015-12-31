<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class JobNurseryType.
 */
class JobNurseryType extends AbstractType
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
            ->add('job', EntityType::class, [
                'label' => 'job_nurseries.label.job',
                'class' => 'AppBundle:Job',
                'choice_label' => 'title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('j')
                        ->where('j.active = TRUE')
                        ->orderBy('j.code');
                },
            ])
            ->add('day', ChoiceType::class, [
                'label' => 'job_nurseries.label.day',
                'choices' => [
                    'days.monday' => 1,
                    'days.tuesday' => 2,
                    'days.wednesday' => 3,
                    'days.thursday' => 4,
                    'days.friday' => 5,
                    'days.saturday' => 6,
                    'days.sunday' => 7,
                ],
                'choices_as_values' => true,
            ])
            ->add('serial', null, ['required' => false, 'label' => 'job_nurseries.label.serial'])
            ->add('numberDays', null, ['label' => 'job_nurseries.label.number_days'])

            ->add('periods', CollectionType::class, [
                'entry_type' => JobNurseryPeriodType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])

            ->add('active', null, ['required' => false, 'label' => 'job_nurseries.label.active', 'attr' => ['disabled' => $disabled]]);

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
            'data_class' => 'AppBundle\\Entity\\JobNursery',
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
