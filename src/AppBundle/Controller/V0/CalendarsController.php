<?php

namespace AppBundle\Controller\V0;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CalendarsController.
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
}
