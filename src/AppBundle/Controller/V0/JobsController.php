<?php

namespace AppBundle\Controller\V0;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JobsController.
 *
 * @Route("/jobs")
 */
class JobsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("", name="app_v0_jobs", methods="GET")
     */
    public function jobsAction(Request $request)
    {
        $transformer = $this->get('app.transformer.job');
        $jobs = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Job')
            ->findBy(['active' => true], ['code' => 'ASC'])
        ;

        return new Response(
            $this->get('serializer')->serialize($transformer->entitiesToModel($jobs), 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
