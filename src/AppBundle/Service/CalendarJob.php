<?php

namespace AppBundle\Service;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\JobCalendar;
use AppBundle\EventListener\JobCalendarCreatedEvent;
use AppBundle\EventListener\JobCalendarDeletedEvent;
use AppBundle\EventListener\JobCalendarEvents;
use AppBundle\EventListener\JobCalendarUpdatedEvent;
use AppBundle\Service\Transformer\AbstractTransformer;
use AppBundle\Service\Transformer\JobCalendarTransformer;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\JobCalendar as Model;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CalendarJob.
 */
class CalendarJob
{
    /** @var EntityManager */
    private $manager;

    /** @var Serializer */
    private $serializer;

    /** @var JobCalendarTransformer */
    private $transformer;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /**
     * @param EntityManager            $manager
     * @param Serializer               $serializer
     * @param JobCalendarTransformer   $transformer
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManager $manager, Serializer $serializer, JobCalendarTransformer $transformer, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->transformer = $transformer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Request  $request
     * @param Calendar $calendar
     *
     * @return Model
     */
    public function createJobCalendarFromRequest(Request $request, Calendar $calendar)
    {
        $jobCalendar = $this->getJobCalendarFromRequest($request);
        $jobCalendar->setCalendar($calendar);
        $calendar->getJobCalendars()->add($jobCalendar);

        $this->manager->flush();

        // Transform entity to complete model and dispatch event
        $this->transformer->setMode(AbstractTransformer::MODE_FULL);
        $completeModel = $this->transformer->entityToModel($jobCalendar);
        $this->transformer->setMode(AbstractTransformer::MODE_LIGHT);

        $this->dispatcher->dispatch(
            JobCalendarEvents::CREATED, new JobCalendarCreatedEvent($completeModel)
        );

        // Return light model
        return $this->transformer->entityToModel($jobCalendar);
    }

    /**
     * @param Request $request
     */
    public function updateJobCalendarFromRequest(Request $request)
    {
        $jobCalendar = $this->getJobCalendarFromRequest($request);
        $this->manager->flush();

        $this->transformer->setMode(AbstractTransformer::MODE_FULL);
        $completeModel = $this->transformer->entityToModel($jobCalendar);

        $this->dispatcher->dispatch(
            JobCalendarEvents::UPDATED, new JobCalendarUpdatedEvent($completeModel)
        );

        $this->transformer->setMode(AbstractTransformer::MODE_LIGHT);
    }

    /**
     * @param JobCalendar $jobCalendar
     */
    public function deleteJobCalendar(JobCalendar $jobCalendar)
    {
        $this->transformer->setMode(AbstractTransformer::MODE_FULL);
        $model = $this->transformer->entityToModel($jobCalendar);

        $this->manager->remove($jobCalendar);
        $this->manager->flush();

        $this->dispatcher->dispatch(
            JobCalendarEvents::DELETED, new JobCalendarDeletedEvent($model)
        );
    }

    /**
     * @param Request $request
     *
     * @return JobCalendar
     */
    private function getJobCalendarFromRequest(Request $request)
    {
        $model = $this->serializer->deserialize(
            $request->getContent(),
            'Ndewez\WebHome\CalendarApiBundle\V0\Model\JobCalendar',
            'json'
        );

        return $this->transformer->modelToEntity($model);
    }
}
