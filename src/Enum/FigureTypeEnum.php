<?php

namespace App\Enum;

class FigureTypeEnum
{
    const CIRCLE = 'circle';
    const TRIANGLE = 'triangle';
    const RECTANGLE = 'rectangle';
    const SQUARE = 'square';
    const RHOMBUS = 'rhombus';
    const TRAPEZOID = 'trapezoid';

    const VALID_TYPES = [
        self::CIRCLE,
        self::TRIANGLE,
        self::RECTANGLE,
        self::SQUARE,
        self::RHOMBUS,
        self::TRAPEZOID,
    ];
}