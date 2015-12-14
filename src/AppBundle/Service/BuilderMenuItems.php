<?php

namespace AppBundle\Service;

use Ndewez\WebHome\CommonBundle\Menu\BuilderMenuItemsInterface;
use Ndewez\WebHome\CommonBundle\Model\MenuItem;

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

        $item = new MenuItem();
        $item
            ->setTitle('menu.home')
            ->setRoute('app_home');

        $items[] = $item;

        return $items;
    }
}
