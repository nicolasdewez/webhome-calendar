<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\GoogleConnection;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleConnection as Model;

/**
 * Class GoogleConnectionTransformer.
 */
class GoogleConnectionTransformer extends AbstractTransformer
{
    public function __construct()
    {
        $this->setMode();
    }

    /**
     * @param GoogleConnection $connection
     *
     * @return Model;
     */
    public function entityToModel(GoogleConnection $connection)
    {
        $model = new Model();
        $model
            ->setId($connection->getId())
            ->setTitle($connection->getTitle())
            ->setClientId($connection->getClientId())
            ->setClientSecret($connection->getClientSecret())
            ->setProjectId($connection->getProjectId())
            ->setProjectId($connection->getProjectId())
            ->setInternalId($connection->getInternalId())
            ->setJobDayComplete($connection->isJobDayComplete())
            ->setNurseryDayComplete($connection->isNurseryDayComplete())
            ->setActive($connection->isActive())
        ;

        return $model;
    }

    /**
     * @param GoogleConnection[] $connections
     *
     * @return Model[]
     */
    public function entitiesToModel(array $connections)
    {
        $models = [];
        foreach ($connections as $connection) {
            $models[] = $this->entityToModel($connection);
        }

        return $models;
    }
}
