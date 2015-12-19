<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GoogleCalendar;
use AppBundle\Form\Type\GoogleCalendarType;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class GoogleCalendarsController.
 *
 * @Route("/google-calendars")
 * @Security("has_role('ROLE_CALD_GOOGL')")
 */
class GoogleCalendarsController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_google_calendars_list", methods="GET")
     * @Security("has_role('ROLE_CALD_GOOGL_SHOW')")
     */
    public function listAction()
    {
        $googleCalendars = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:GoogleCalendar')->findBy([], ['id' => 'ASC']);

        return $this->render('googleCalendars/list.html.twig', ['googleCalendars' => $googleCalendars]);
    }

    /**
     * @param GoogleCalendar $googleCalendar
     * @param Request        $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_google_calendars_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_GOOGL_EDIT')")
     */
    public function editAction(GoogleCalendar $googleCalendar, Request $request)
    {
        $form = $this->get('form.factory')->create(GoogleCalendarType::class, $googleCalendar, ['delete' => $this->isGoogleCalendarDeletable(), 'activate' => $this->isGoogleCalendarActivate()]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                $manager->remove($googleCalendar);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('google_calendars.message.delete'));

                return new RedirectResponse($this->generateUrl('app_google_calendars_list'));
            }

            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('google_calendars.message.edit'));
        }

        return $this->render('googleCalendars/edit.html.twig', ['form' => $form->createView(), 'googleCalendar' => $googleCalendar]);
    }

    /**
     * @param GoogleCalendar $googleCalendar
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_google_calendars_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_CALD_GOOGL_SHOW')")
     */
    public function showAction(GoogleCalendar $googleCalendar)
    {
        $form = $this->get('form.factory')->create(GoogleCalendarType::class, $googleCalendar, ['submit' => false]);

        return $this->render('googleCalendars/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_google_calendars_add", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_GOOGL_ADD')")
     */
    public function addAction(Request $request)
    {
        $googleCalendar = new GoogleCalendar();
        $form = $this->get('form.factory')->create(GoogleCalendarType::class, $googleCalendar);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($googleCalendar);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('google_calendars.message.add'));

            return new RedirectResponse($this->generateUrl('app_google_calendars_edit', ['id' => $googleCalendar->getId()]));
        }

        return $this->render('googleCalendars/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param GoogleCalendar $googleCalendar
     * @param Request        $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_google_calendars_activate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_GOOGL_ACTIV')")
     */
    public function activateAction(GoogleCalendar $googleCalendar, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGoogleCalendarDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('google_calendars.error.not_activate'));
        }

        $googleCalendar->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('google_calendars.message.active')]);
    }

    /**
     * @param GoogleCalendar $googleCalendar
     * @param Request        $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_google_calendars_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_GOOGL_ACTIV')")
     */
    public function deactivateAction(GoogleCalendar $googleCalendar, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGoogleCalendarDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('google_calendars.error.not_deactivate'));
        }

        $googleCalendar->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('google_calendars.message.inactive')]);
    }

    /**
     * @param GoogleCalendar $googleCalendar
     * @param Request        $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_google_calendars_delete", methods="DELETE")
     * @Security("has_role('ROLE_CALD_GOOGL_DEL')")
     */
    public function deleteAction(GoogleCalendar $googleCalendar, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($googleCalendar);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('google_calendars.message.delete')]);
    }

    /**
     * @return bool
     */
    private function isGoogleCalendarDeletable()
    {
        return $this->isGranted('ROLE_CALD_GOOGL_DEL');
    }

    /**
     * @return bool
     */
    private function isGoogleCalendarActivate()
    {
        return $this->isGranted('ROLE_CALD_GOOGL_ACTIV');
    }
}
