<?php

namespace App\ValueObject;

class ChangedObjectValue
{
    public $field;
    public $oldValue;
    public $newValue;

    public function __construct(
        string $field,
        $oldValue,
        $newValue
    )
    {
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }
}