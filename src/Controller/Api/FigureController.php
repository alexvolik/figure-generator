<?php

namespace App\Controller\Api;

use App\Repository\FigureChangeListRepository;
use App\Repository\FigureRepository;
use App\Service\FigureService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class FigureController extends AbstractFOSRestController
{
    private $figureService;
    private $figureRepository;
    private $changeListRepository;

    public function __construct(
        FigureService $figureService,
        FigureRepository $figureRepository,
        FigureChangeListRepository $changeListRepository
    )
    {
        $this->figureService = $figureService;
        $this->figureRepository = $figureRepository;
        $this->changeListRepository = $changeListRepository;
    }

    /**
     * @Rest\Get("/api/figures/{batchId}")
     *
     * @Rest\View()
     */
    public function getByBatchId(string $batchId)
    {
        return [
            'figures' => $this->figureRepository->findByBatchId($batchId, ['createdAt' => 'ASC']),
        ];
    }

    /**
     * @Rest\Get("/api/figures/{batchId}/changes")
     *
     * @Rest\View()
     */
    public function getChangesByBatchId(string $batchId)
    {
        return [
            'changes' => $this->changeListRepository->findChangesByBatchId($batchId),
        ];
    }

    /**
     * @Rest\Post("/api/figures/generate")
     *
     * @Rest\View()
     */
    public function postGenerateAction()
    {
        $figures = $this->figureService->getRandomFigures();

        return [
            'figures' => $figures,
            'batchId' => reset($figures)->getBatchId(),
        ];
    }

    /**
     * @Rest\Post("/api/figures/{batchId}/change")
     *
     * @Rest\View()
     */
    public function postChangeAction(string $batchId)
    {
        return $this->figureService->changeRandomFigure($batchId);
    }
}