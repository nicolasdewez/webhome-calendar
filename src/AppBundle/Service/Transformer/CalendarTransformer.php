<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\Calendar;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\Calendar as Model;

/**
 * Class CalendarTransformer.
 */
class CalendarTransformer
{
    /**
     * @param Calendar $calendar
     *
     * @return Model;
     */
    public function entityToModel(Calendar $calendar)
    {
        $model = new Model();
        $model
            ->setId($calendar->getId())
            ->setTitle($calendar->getTitle())
        ;

        return $model;
    }

    /**
     * @param Calendar[] $calendars
     *
     * @return Model[]
     */
    public function entitiesToModel(array $calendars)
    {
        $models = [];
        foreach($calendars as $calendar) {
            $models[] = $this->entityToModel($calendar);
        }

        return $models;
    }
}
