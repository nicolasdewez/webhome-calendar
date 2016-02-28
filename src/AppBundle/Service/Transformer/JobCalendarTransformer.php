<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\JobCalendar;
use Doctrine\ORM\EntityManager;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\JobCalendar as Model;

/**
 * Class JobCalendarTransformer.
 */
class JobCalendarTransformer extends AbstractTransformer
{
    /** @var EntityManager */
    private $manager;

    /** @var JobTransformer */
    private $jobTransformer;

    /** @var CalendarTransformer */
    private $calendarTransformer;

    /**
     * @param EntityManager       $manager
     * @param JobTransformer      $jobTransformer
     * @param CalendarTransformer $calendarTransformer
     */
    public function __construct(EntityManager $manager, JobTransformer $jobTransformer, CalendarTransformer $calendarTransformer)
    {
        $this->manager = $manager;
        $this->jobTransformer = $jobTransformer;
        $this->calendarTransformer = $calendarTransformer;

        $this->setMode();
    }

    /**
     * @param JobCalendar $jobCalendar
     *
     * @return Model;
     */
    public function entityToModel(JobCalendar $jobCalendar)
    {
        $model = new Model();
        $model
            ->setId($jobCalendar->getId())
            ->setDate($jobCalendar->getDate())
            ->setJob($this->jobTransformer->entityToModel($jobCalendar->getJob()))
        ;

        if ($this->isModeFull()) {
            $model->setCalendar($this->calendarTransformer->entityToModel($jobCalendar->getCalendar()));
        }

        return $model;
    }

    /**
     * @param JobCalendar[] $jobCalendars
     *
     * @return Model[]
     */
    public function entitiesToModel(array $jobCalendars)
    {
        $models = [];
        foreach ($jobCalendars as $jobCalendar) {
            $models[] = $this->entityToModel($jobCalendar);
        }

        return $models;
    }

    /**
     * @param Model $model
     *
     * @return JobCalendar
     */
    public function modelToEntity(Model $model)
    {
        // Load entity if id, else init new object
        $jobCalendar = new JobCalendar();
        if (null !== $model->getId()) {
            $jobCalendar = $this->manager->getRepository('AppBundle:JobCalendar')->find($model->getId());
        }

        // Load dependencies
        if (null !== $model->getJob() && null !== $model->getJob()->getId()) {
            $job = $this->manager->getRepository('AppBundle:Job')->find($model->getJob()->getId());
            if (null !== $job) {
                $jobCalendar->setJob($job);
            }
        }

        if (null !== $model->getCalendar() && null !== $model->getCalendar()->getId()) {
            $calendar = $this->manager->getRepository('AppBundle:Calendar')->find($model->getCalendar()->getId());
            if (null !== $calendar) {
                $jobCalendar->setCalendar($calendar);
            }
        }

        // Set others attributes
        return $jobCalendar->setDate($model->getDate());
    }

    /**
     * {@inheritdoc}
     */
    public function setMode($mode = AbstractTransformer::MODE_LIGHT)
    {
        parent::setMode($mode);
        $this->calendarTransformer->setMode($mode);
    }
}
