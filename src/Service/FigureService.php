<?php

namespace App\Service;

use App\Entity\Figure;
use App\Factory\FigureFactory;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class FigureService
{
    private const MIN_FIGURES = 1;
    private const MAX_FIGURES = 5;

    private $figureFactory;
    private $figureRepository;
    private $em;

    public function __construct(FigureFactory $figureFactory, FigureRepository $figureRepository, EntityManagerInterface $em)
    {
        $this->figureFactory = $figureFactory;
        $this->figureRepository = $figureRepository;
        $this->em = $em;
    }

    public function getRandomFigures(): array
    {
        $figures = [];
        $batchId = Uuid::uuid4()->toString();

        for ($i = 1; $i <= random_int(self::MIN_FIGURES, self::MAX_FIGURES); $i++) {
            $figure = $this->figureFactory->create($batchId);
            $this->em->persist($figure);

            $figures[] = $figure;
        }

        $this->em->flush();

        return $figures;
    }

    public function changeRandomFigure(string $batchId): ?Figure
    {
        $figures = $this->figureRepository->findByBatchId($batchId);
        if (empty($figures)) {
            return null;
        }

        $figure = $figures[array_rand($figures, 1)];

        $figure->changeColor();

        $this->figureRepository->save($figure);

        return $figure;
    }
}