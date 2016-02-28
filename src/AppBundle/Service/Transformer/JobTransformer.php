<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\Job;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\Job as Model;

/**
 * Class JobTransformer.
 */
class JobTransformer extends AbstractTransformer
{
    public function __construct()
    {
        $this->setMode();
    }

    /**
     * @param Job $job
     *
     * @return Model;
     */
    public function entityToModel(Job $job)
    {
        $model = new Model();
        $model
            ->setId($job->getId())
            ->setCode($job->getCode())
            ->setTitle($job->getTitle())
            ->setActive($job->isActive())
        ;

        if ($this->isModeFull()) {
            $model
                ->setStartTime($job->getStartTime())
                ->setEndTime($job->getEndTime())
                ->setDuration($job->getDuration())
            ;
        }

        return $model;
    }

    /**
     * @param Job[] $calendars
     *
     * @return Model[]
     */
    public function entitiesToModel(array $calendars)
    {
        $models = [];
        foreach ($calendars as $calendar) {
            $models[] = $this->entityToModel($calendar);
        }

        return $models;
    }
}
