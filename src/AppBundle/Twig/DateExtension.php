<?php

namespace AppBundle\Twig;

/**
 * Class DateExtension.
 */
class DateExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('day', [$this, 'day']),
        ];
    }

    /**
     * @param int $indexDay
     *
     * @return string : code translation
     */
    public function day($indexDay)
    {
        $indexDay %= 7;

        switch ($indexDay) {
            case 1: return 'days.monday';
            case 2: return 'days.tuesday';
            case 3: return 'days.wednesday';
            case 4: return 'days.thursday';
            case 5: return 'days.friday';
            case 6: return 'days.saturday';
            case 0: return 'days.sunday';
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_date';
    }
}
