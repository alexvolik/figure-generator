<?php

namespace App\Controller\Api;

use App\Entity\EntityChanges;
use App\Handler\EntityChangesHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class FigureChangeListController extends AbstractFOSRestController
{
    private $entityChangesHandler;

    public function __construct(EntityChangesHandler $entityChangesHandler)
    {
        $this->entityChangesHandler = $entityChangesHandler;
    }

    /**
     * @Rest\Post("/api/figure-changes/{figureChanges}/revert")
     *
     * @Rest\View()
     */
    public function postRevertAction(EntityChanges $figureChanges)
    {
        $this->entityChangesHandler->revert($figureChanges);
    }
}