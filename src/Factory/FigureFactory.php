<?php

namespace App\Factory;

use App\Entity\Figure;
use App\Enum\FigureTypeEnum;

class FigureFactory
{
    public function create(string $batchId = null, string $type = null, string $color = null): Figure
    {
        if (empty($type)) {
            $type = FigureTypeEnum::VALID_TYPES[array_rand(FigureTypeEnum::VALID_TYPES, 1)];
        }

        if (empty($color)) {
            $color = sprintf('#%06X', random_int(0, 0xFFFFFF));
        }

        return new Figure($type, $color, $batchId);
    }
}