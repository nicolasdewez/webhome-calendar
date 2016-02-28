<?php

namespace AppBundle\Controller\V0;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\JobCalendar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CalendarsController.
 *
 * @Route("/calendars")
 */
class CalendarsController extends Controller
{
    /**
     * @return Response
     *
     * @Route("", name="app_v0_calendars", methods="GET")
     */
    public function calendarsAction()
    {
        $transformer = $this->get('app.transformer.calendar');
        $calendars = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Calendar')
            ->findBy(['active' => true], ['title' => 'ASC'])
        ;

        return new Response(
            $this->get('serializer')->serialize($transformer->entitiesToModel($calendars), 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @param Calendar $calendar
     *
     * @return Response
     *
     * @Route("/{id}/jobs", name="app_v0_calendars_jobcalendars", methods="GET")
     */
    public function getJobCalendarsAction(Calendar $calendar)
    {
        $transformer = $this->get('app.transformer.job_calendar');

        return new Response(
            $this->get('serializer')->serialize($transformer->entitiesToModel($calendar->getJobCalendars()->toArray()), 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @param Calendar $calendar
     * @param Request  $request
     *
     * @return Response
     *
     * @Route("/{id}/jobs", name="app_v0_calendars_create_jobcalendars", methods="POST")
     */
    public function createJobCalendarAction(Calendar $calendar, Request $request)
    {
        $model = $this->get('app.calendar.job')->createJobCalendarFromRequest($request, $calendar);

        return new Response(
            $this->get('serializer')->serialize($model, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}/jobs", name="app_v0_calendars_update_jobcalendars", methods="PUT")
     */
    public function updateJobCalendarAction(Request $request)
    {
        $this->get('app.calendar.job')->updateJobCalendarFromRequest($request);

        return new Response('', Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    /**
     * @param JobCalendar $jobCalendar
     *
     * @return Response
     *
     * @Route("/{calendarId}/jobs/{id}", name="app_v0_calendars_delete_jobcalendars", methods="DELETE")
     */
    public function deleteJobCalendarAction(JobCalendar $jobCalendar)
    {
        $this->get('app.calendar.job')->deleteJobCalendar($jobCalendar);

        return new Response('', Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}
