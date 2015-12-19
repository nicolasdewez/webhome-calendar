<?php

namespace AppBundle\Twig;

/**
 * Class TimeExtension.
 */
class TimeExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('mnToH', [$this, 'minuteToHour']),
        ];
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public function minuteToHour($value)
    {
        $nbHours = floor($value/60);
        $nbMinutes = $value % 60;

        return $nbHours.'h'.str_pad($nbMinutes, '2', '0', STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_time';
    }
}
