<?php

namespace AppBundle\Controller;

use AppBundle\Entity\JobNursery;
use AppBundle\Form\Type\JobNurseryType;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JobNurseriesController.
 *
 * @Route("/job-nurseries")
 * @Security("has_role('ROLE_CALD_NURS')")
 */
class JobNurseriesController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_job_nurseries_list", methods="GET")
     * @Security("has_role('ROLE_CALD_NURS_SHOW')")
     */
    public function listAction()
    {
        $manager = $this->get('doctrine.orm.entity_manager');
        $jobNurseries = $manager->getRepository('AppBundle:JobNursery')->findBy([], ['day' => 'ASC']);

        return $this->render('jobNurseries/list.html.twig', ['jobNurseries' => $jobNurseries]);
    }

    /**
     * @param JobNursery $jobNursery
     * @param Request    $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_job_nurseries_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_NURS_EDIT')")
     */
    public function editAction(JobNursery $jobNursery, Request $request)
    {
        $nurseryPeriod = $this->get('app.nursery.period');
        $originalPeriods = $nurseryPeriod->getPeriods($jobNursery);
        $form = $this->get('form.factory')->create(JobNurseryType::class, $jobNursery, [
            'delete' => $this->isGranted('ROLE_CALD_NURS_DEL'),
            'activate' => $this->isGranted('ROLE_CALD_NURS_ACTIV'),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                $manager->remove($jobNursery);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('job_nurseries.message.delete'));

                return new RedirectResponse($this->generateUrl('app_job_nurseries_list'));
            }

            $nurseryPeriod->cleanPeriods($jobNursery, $originalPeriods);
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('job_nurseries.message.edit'));

            return new RedirectResponse($this->generateUrl('app_job_nurseries_edit', ['id' => $jobNursery->getId()]));
        }

        return $this->render('jobNurseries/edit.html.twig', ['form' => $form->createView(), 'jobNursery' => $jobNursery]);
    }

    /**
     * @param JobNursery $jobNursery
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_job_nurseries_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_CALD_NURS_SHOW')")
     */
    public function showAction(JobNursery $jobNursery)
    {
        $form = $this->get('form.factory')->create(JobNurseryType::class, $jobNursery, ['submit' => false]);

        return $this->render('jobNurseries/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_job_nurseries_add", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_NURS_ADD')")
     */
    public function addAction(Request $request)
    {
        $jobNursery = new JobNursery();
        $form = $this->get('form.factory')->create(JobNurseryType::class, $jobNursery);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($jobNursery);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('job_nurseries.message.add'));

            return new RedirectResponse($this->generateUrl('app_job_nurseries_edit', ['id' => $jobNursery->getId()]));
        }

        return $this->render('jobNurseries/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param JobNursery $jobNursery
     * @param Request    $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_job_nurseries_activate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_NURS_ACTIV')")
     */
    public function activateAction(JobNursery $jobNursery, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $jobNursery->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('job_nurseries.message.active')]);
    }

    /**
     * @param JobNursery $jobNursery
     * @param Request    $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_job_nurseries_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_NURS_ACTIV')")
     */
    public function deactivateAction(JobNursery $jobNursery, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $jobNursery->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('job_nurseries.message.inactive')]);
    }

    /**
     * @param JobNursery $jobNursery
     * @param Request    $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_job_nurseries_delete", methods="DELETE")
     * @Security("has_role('ROLE_CALD_NURS_DEL')")
     */
    public function deleteAction(JobNursery $jobNursery, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($jobNursery);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('job_nurseries.message.delete')]);
    }
}
