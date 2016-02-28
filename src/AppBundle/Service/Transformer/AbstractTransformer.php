<?php

namespace AppBundle\Service\Transformer;

/**
 * Class AbstractTransformer.
 */
abstract class AbstractTransformer
{
    const MODE_LIGHT = 'light';
    const MODE_FULL = 'full';

    /** @var string */
    protected $mode;

    /**
     * @param string $mode
     */
    public function setMode($mode = self::MODE_LIGHT)
    {
        $this->mode = $mode;
    }

    /**
     * @return bool
     */
    protected function isModeLight()
    {
        return self::MODE_LIGHT === $this->mode;
    }

    /**
     * @return bool
     */
    protected function isModeFull()
    {
        return self::MODE_FULL === $this->mode;
    }
}
