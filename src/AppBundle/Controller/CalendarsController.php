<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Calendar;
use AppBundle\Form\Type\CalendarType;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class CalendarsController.
 *
 * @Route("/calendars")
 * @Security("has_role('ROLE_CALD_CALD')")
 */
class CalendarsController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_calendars_list", methods="GET")
     * @Security("has_role('ROLE_CALD_CALD_SHOW')")
     */
    public function listAction()
    {
        $calendars = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Calendar')->findBy([], ['id' => 'ASC']);

        return $this->render('calendars/list.html.twig', ['calendars' => $calendars]);
    }

    /**
     * @param Calendar     $calendar
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_calendars_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_CALD_EDIT')")
     */
    public function editAction(Calendar $calendar, Request $request)
    {
        $form = $this->get('form.factory')->create(CalendarType::class, $calendar, ['delete' => $this->isCalendarDeletable(), 'activate' => $this->isCalendarActivate()]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                $manager->remove($calendar);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('calendars.message.delete'));

                return new RedirectResponse($this->generateUrl('app_calendars_list'));
            }

            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('calendars.message.edit'));
        }

        return $this->render('calendars/edit.html.twig', ['form' => $form->createView(), 'calendar' => $calendar]);
    }

    /**
     * @param Calendar $calendar
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_calendars_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_CALD_CALD_SHOW')")
     */
    public function showAction(Calendar $calendar)
    {
        $form = $this->get('form.factory')->create(CalendarType::class, $calendar, ['submit' => false]);

        return $this->render('calendars/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_calendars_add", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_CALD_ADD')")
     */
    public function addAction(Request $request)
    {
        $calendar = new Calendar();
        $form = $this->get('form.factory')->create(CalendarType::class, $calendar);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($calendar);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('calendars.message.add'));

            return new RedirectResponse($this->generateUrl('app_calendars_edit', ['id' => $calendar->getId()]));
        }

        return $this->render('calendars/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Calendar     $calendar
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_calendars_activate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_CALD_ACTIV')")
     */
    public function activateAction(Calendar $calendar, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isCalendarDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('calendars.error.not_activate'));
        }

        $calendar->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('calendars.message.active')]);
    }

    /**
     * @param Calendar     $calendar
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_calendars_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_CALD_ACTIV')")
     */
    public function deactivateAction(Calendar $calendar, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isCalendarDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('calendars.error.not_deactivate'));
        }

        $calendar->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('calendars.message.inactive')]);
    }

    /**
     * @param Calendar     $calendar
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_calendars_delete", methods="DELETE")
     * @Security("has_role('ROLE_CALD_CALD_DEL')")
     */
    public function deleteAction(Calendar $calendar, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($calendar);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('calendars.message.delete')]);
    }

    /**
     * @return bool
     */
    private function isCalendarDeletable()
    {
        return $this->isGranted('ROLE_CALD_CALD_DEL');
    }

    /**
     * @return bool
     */
    private function isCalendarActivate()
    {
        return $this->isGranted('ROLE_CALD_CALD_ACTIV');
    }
}
