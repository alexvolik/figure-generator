<?php

namespace App\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class LazyCollection
{
    public $targetEntity;

    public $targetColumnName;
}