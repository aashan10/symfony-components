<?php

namespace Aashan\Workflow\Models;

use Aashan\Workflow\Collections\AbstractCollection;
use Aashan\Workflow\Collections\Collection;
use Aashan\Workflow\DataModel\DataModel;
use Aashan\Workflow\Helpers\StorageHelper;
use JetBrains\PhpStorm\Pure;

abstract class AbstractModel extends DataModel
{
    protected static string $collectionName = 'model';
    protected static string $idField = 'id';
    protected static string $collectionClass = Collection::class;

    #[Pure]
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function getId()
    {
        return $this->getData(static::$idField);
    }

    public function toJson(): bool|string
    {
        return json_encode($this->data);
    }

    public static function fromJson(string $json): self
    {
        return new static(json_decode($json, true));
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public static function find(string $id): bool|DataModel
    {
        $storage = new StorageHelper();
        $collection = $storage->getCollection(static::$collectionName, static::class, static::$collectionClass);
        return $collection->find($id);
    }

    public function save()
    {
        $storageHelper = new StorageHelper();
        $collection = $storageHelper->getCollection(static::$collectionName, static::class);
        $collection = $collection->filter(function(DataModel $model) {
           return (string)$model->getData(static::$idField) !== (string)$this->getData(static::$idField);
        });
        $collection->add($this);

        $storageHelper->saveCollection(static::$collectionName, $collection);
    }

    public static function getCollection(): AbstractCollection
    {
        $storageHelper = new StorageHelper();
        return $storageHelper->getCollection(static::$collectionName, static::$collectionClass);
    }
}