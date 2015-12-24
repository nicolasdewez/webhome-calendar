<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\GoogleConnection;
use AppBundle\Form\Type\GoogleConnectionType;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class GoogleConnectionsController.
 *
 * @Route("/google-connections")
 * @Security("has_role('ROLE_CALD_GOOGL')")
 */
class GoogleConnectionsController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_google_connections_list", methods="GET")
     * @Security("has_role('ROLE_CALD_GOOGL_SHOW')")
     */
    public function listAction()
    {
        $googleConnections = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:GoogleConnection')->findBy([], ['id' => 'ASC']);

        return $this->render('googleConnections/list.html.twig', ['googleConnections' => $googleConnections]);
    }

    /**
     * @param Calendar $calendar
     *
     * @return Response
     *
     * @Route("/calendar/{id}", name="app_google_connections_list_calendar", methods="GET")
     * @Security("has_role('ROLE_CALD_GOOGL_SHOW')")
     */
    public function listByCalendarAction(Calendar $calendar)
    {
        return $this->render('googleConnections/listByCalendar.html.twig', [
            'googleConnections' => $calendar->getGoogleConnections(),
            'calendar' => $calendar,
        ]);
    }

    /**
     * @param GoogleConnection $googleConnection
     * @param Request          $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_google_connections_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_GOOGL_EDIT')")
     */
    public function editAction(GoogleConnection $googleConnection, Request $request)
    {
        $form = $this->get('form.factory')->create(GoogleConnectionType::class, $googleConnection, ['delete' => $this->isGoogleConnectionDeletable(), 'activate' => $this->isGoogleConnectionActivate()]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                $manager->remove($googleConnection);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('google_connections.message.delete'));

                return new RedirectResponse($this->generateUrl('app_google_connections_list'));
            }

            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('google_connections.message.edit'));
        }

        return $this->render('googleConnections/edit.html.twig', ['form' => $form->createView(), 'googleConnection' => $googleConnection]);
    }

    /**
     * @param GoogleConnection $googleConnection
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_google_connections_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_CALD_GOOGL_SHOW')")
     */
    public function showAction(GoogleConnection $googleConnection)
    {
        $form = $this->get('form.factory')->create(GoogleConnectionType::class, $googleConnection, ['submit' => false]);

        return $this->render('googleConnections/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_google_connections_add", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CALD_GOOGL_ADD')")
     */
    public function addAction(Request $request)
    {
        $googleConnection = new GoogleConnection();
        $form = $this->get('form.factory')->create(GoogleConnectionType::class, $googleConnection);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($googleConnection);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('google_connections.message.add'));

            return new RedirectResponse($this->generateUrl('app_google_connections_edit', ['id' => $googleConnection->getId()]));
        }

        return $this->render('googleConnections/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param GoogleConnection $googleConnection
     * @param Request          $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_google_connections_activate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_GOOGL_ACTIV')")
     */
    public function activateAction(GoogleConnection $googleConnection, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGoogleConnectionDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('google_connections.error.not_activate'));
        }

        $googleConnection->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('google_connections.message.active')]);
    }

    /**
     * @param GoogleConnection $googleConnection
     * @param Request          $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_google_connections_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_CALD_GOOGL_ACTIV')")
     */
    public function deactivateAction(GoogleConnection $googleConnection, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGoogleConnectionDeletable()) {
            throw new BadRequestHttpException($this->get('translator')->trans('google_connections.error.not_deactivate'));
        }

        $googleConnection->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('google_connections.message.inactive')]);
    }

    /**
     * @param GoogleConnection $googleConnection
     * @param Request          $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_google_connections_delete", methods="DELETE")
     * @Security("has_role('ROLE_CALD_GOOGL_DEL')")
     */
    public function deleteAction(GoogleConnection $googleConnection, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($googleConnection);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('google_connections.message.delete')]);
    }

    /**
     * @return bool
     */
    private function isGoogleConnectionDeletable()
    {
        return $this->isGranted('ROLE_CALD_GOOGL_DEL');
    }

    /**
     * @return bool
     */
    private function isGoogleConnectionActivate()
    {
        return $this->isGranted('ROLE_CALD_GOOGL_ACTIV');
    }
}
