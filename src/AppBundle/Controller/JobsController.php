<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Form\Type\JobType;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class JobsController.
 *
 * @Route("/jobs")
 * @Security("has_role('ROLE_CALD_JOBS')")
 */
class JobsController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_jobs_list", methods="GET")
     * @Security("has_role('ROLE_CALD_JOBS_SHOW')")
     */
    public function listAction()
    {
        $jobs = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Job')->findBy([], ['code' => 'ASC']);

        return $this->render('jobs/list.html.twig', ['jobs' => $jobs]);
    }

    /**
     * @param Job     $job
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_jobs_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_JOBS_EDIT')")
     */
    public function editAction(Job $job, Request $request)
    {
        $form = $this->get('form.factory')->create(JobType::class, $job, ['delete' => $this->isJobDeletable(), 'activate' => $this->isJobActivate()]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                if ($job->hasUser()) {
                    $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('jobs.error.contains_user'));

                    return new RedirectResponse($this->generateUrl('app_jobs_list'));
                }

                // Delete element and redirect
                $manager->remove($job);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('jobs.message.delete'));

                return new RedirectResponse($this->generateUrl('app_jobs_list'));
            }
            $job->calculateDuration();
            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('jobs.message.edit'));
        }

        return $this->render('jobs/edit.html.twig', ['form' => $form->createView(), 'job' => $job]);
    }

    /**
     * @param Job $job
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_jobs_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_CALD_JOBS_SHOW')")
     */
    public function showAction(Job $job)
    {
        $form = $this->get('form.factory')->create(JobType::class, $job, ['submit' => false]);

        return $this->render('jobs/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_jobs_add", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_JOBS_ADD')")
     */
    public function addAction(Request $request)
    {
        $job = new Job();
        $form = $this->get('form.factory')->create(JobType::class, $job);

        if ($form->handleRequest($request) && $form->isValid()) {
            $job->calculateDuration();
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($job);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('jobs.message.add'));

            return new RedirectResponse($this->generateUrl('app_jobs_edit', ['id' => $job->getId()]));
        }

        return $this->render('jobs/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Job     $job
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_jobs_activate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_JOBS_ACTIV')")
     */
    public function activateAction(Job $job, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isJobDeletable($job)) {
            throw new BadRequestHttpException($this->get('translator')->trans('jobs.error.not_activate'));
        }

        $job->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('jobs.message.active')]);
    }

    /**
     * @param Job     $job
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_jobs_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_JOBS_ACTIV')")
     */
    public function deactivateAction(Job $job, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isJobDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('jobs.error.not_deactivate'));
        }

        $job->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('jobs.message.inactive')]);
    }

    /**
     * @param Job     $job
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_jobs_delete", methods="DELETE")
     * @Security("has_role('ROLE_CALD_JOBS_DEL')")
     */
    public function deleteAction(Job $job, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($job);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('jobs.message.delete')]);
    }

    /**
     * @return bool
     */
    private function isJobDeletable()
    {
        return $this->isGranted('ROLE_CALD_JOBS_DEL');
    }

    /**
     * @return bool
     */
    private function isJobActivate()
    {
        return $this->isGranted('ROLE_CALD_JOBS_ACTIV');
    }
}
