<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SearchCalendarType.
 */
class SearchCalendarType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('calendar', EntityType::class, [
                'label' => 'reports.search.calendar',
                'class' => 'AppBundle:Calendar',
                'choice_label' => 'title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.active = TRUE')
                        ->orderBy('c.id');
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'actions.search', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
