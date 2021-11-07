<?php

namespace Aashan\Workflow\Collections;

use Aashan\Workflow\DataModel\DataModel;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractCollection extends ArrayCollection
{
    protected static string $primaryField = 'id';

    public function find(string $id): DataModel|false
    {
        return $this->filter(function(object $dataObject) use($id) {
            return (string) $dataObject->getData(static::$primaryField) === $id;
        })->first();
    }
}