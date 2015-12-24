<?php

namespace AppBundle\Service;

use Ndewez\WebHome\CommonBundle\Menu\BuilderMenuItemsInterface;
use Ndewez\WebHome\CommonBundle\Model\Authorization as AuthorizationModel;
use Ndewez\WebHome\CommonBundle\Model\MenuItemDivider;
use Ndewez\WebHome\CommonBundle\Model\MenuItemLink;

/**
 * Class BuilderMenu.
 */
class BuilderMenuItems implements BuilderMenuItemsInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(array $authorizations)
    {
        if (0 === count($authorizations)) {
            return [];
        }

        $item = new MenuItemLink();
        $item
            ->setTitle('menu.home')
            ->setRoute('app_home');

        $items[] = $item;

        if (
            $this->isGranted('ROLE_CALD_JOBS', $authorizations) ||
            $this->isGranted('ROLE_CALD_NURS', $authorizations) ||
            $this->isGranted('ROLE_CALD_GOOGL', $authorizations) ||
            $this->isGranted('ROLE_CALD_CALD', $authorizations)
        ) {
            $item = new MenuItemLink();
            $item->setTitle('menu.parameters.title');

            if ($this->isGranted('ROLE_CALD_JOBS', $authorizations)) {
                $subItem = new MenuItemLink();
                $subItem
                    ->setTitle('menu.parameters.job')
                    ->setRoute('app_jobs_list');

                $item->addItem($subItem);
            }

            if ($this->isGranted('ROLE_CALD_NURS', $authorizations)) {
                $subItem = new MenuItemLink();
                $subItem
                    ->setTitle('menu.parameters.job_nurseries')
                    ->setRoute('app_home');

                $item->addItem($subItem);
            }

            if ($this->isGranted('ROLE_CALD_JOBS', $authorizations) || $this->isGranted('ROLE_CALD_NURS', $authorizations)) {
                $item->addItem(new MenuItemDivider());
            }

            if ($this->isGranted('ROLE_CALD_GOOGL', $authorizations)) {
                $subItem = new MenuItemLink();
                $subItem
                    ->setTitle('menu.parameters.google')
                    ->setRoute('app_google_connections_list');

                $item->addItem($subItem);
            }

            if ($this->isGranted('ROLE_CALD_CALD', $authorizations)) {
                $subItem = new MenuItemLink();
                $subItem
                    ->setTitle('menu.parameters.calendar')
                    ->setRoute('app_calendars_list');

                $item->addItem($subItem);
            }

            $items[] = $item;
        }

        if ($this->isGranted('ROLE_CALD_REPRT', $authorizations)) {
            $item = new MenuItemLink();
            $item->setTitle('menu.reports.title');

            if ($this->isGranted('ROLE_CALD_REPRT_JOB', $authorizations)) {
                $subItem = new MenuItemLink();
                $subItem
                    ->setTitle('menu.reports.job')
                    ->setRoute('app_reports_jobs_list');

                $item->addItem($subItem);
            }

            if ($this->isGranted('ROLE_CALD_REPRT_NURS', $authorizations)) {
                $subItem = new MenuItemLink();
                $subItem
                    ->setTitle('menu.reports.nursery')
                    ->setRoute('app_reports_nurseries_list');

                $item->addItem($subItem);
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @param string               $code
     * @param AuthorizationModel[] $authorizations
     *
     * @return bool
     */
    private function isGranted($code, array $authorizations)
    {
        foreach ($authorizations as $authorization) {
            if ($code === $authorization->getCodeAction()) {
                return $authorization->isGranted();
            }
        }

        return false;
    }
}
