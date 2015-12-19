<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SearchCalendarType;
use AppBundle\Model\Report;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReportsController.
 *
 * @Route("/reports")
 * @Security("has_role('ROLE_CALD_REPRT')")
 */
class ReportsController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/jobs", name="app_reports_jobs_list", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_REPRT_JOB')")
     */
    public function jobsAction(Request $request)
    {
        $form = $this->get('form.factory')->create(SearchCalendarType::class, null);
        $reports = null;

        if ($form->handleRequest($request) && $form->isValid()) {
            $reportService = $this->get('app.report.job');
            $report = $reportService->build($form->getData()['calendar']);
            $reports = $reportService->getDisplayingVersion($report);
        }

        return $this->render('reports/jobs.html.twig', ['form' => $form->createView(), 'reports' => $reports]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/nurseries", name="app_reports_nurseries_list", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_REPRT_NURS')")
     */
    public function nurseriesAction(Request $request)
    {
        $form = $this->get('form.factory')->create(SearchCalendarType::class, null);
        $reports = null;

        if ($form->handleRequest($request) && $form->isValid()) {
            $reportService = $this->get('app.report.nursery');
            $report = $reportService->build($form->getData()['calendar']);
            $reports = $reportService->getDisplayingVersion($report);
        }

        return $this->render('reports/nurseries.html.twig', ['form' => $form->createView(), 'reports' => $reports]);
    }
}
