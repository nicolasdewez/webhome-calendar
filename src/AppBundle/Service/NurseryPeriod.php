<?php

namespace AppBundle\Service;

use AppBundle\Entity\JobNursery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

/**
 * Class NurseryPeriod.
 */
class NurseryPeriod
{
    /** @var EntityManager */
    private $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param JobNursery $jobNursery
     *
     * @return ArrayCollection
     */
    public function getPeriods(JobNursery $jobNursery)
    {
        $periods = new ArrayCollection();
        foreach ($jobNursery->getPeriods() as $period) {
            $periods->add($period);
        }

        return $periods;
    }

    /**
     * @param JobNursery      $jobNursery
     * @param ArrayCollection $originalPeriods
     * @param bool            $flush
     */
    public function cleanPeriods(JobNursery $jobNursery, ArrayCollection $originalPeriods, $flush = true)
    {
        foreach ($originalPeriods as $period) {
            if (false === $jobNursery->getPeriods()->contains($period)) {
                $this->manager->remove($period);
            }
        }

        if ($flush) {
            $this->manager->flush();
        }
    }
}
