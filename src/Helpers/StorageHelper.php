<?php

namespace Aashan\Workflow\Helpers;

use Aashan\Workflow\Collections\AbstractCollection;
use Aashan\Workflow\Collections\Collection;
use Aashan\Workflow\Collections\Order\OrderCollection;
use Aashan\Workflow\DataModel\DataModel;
use Aashan\Workflow\Models\Order;

class StorageHelper
{

    protected string $storageDirectory = BASE_PATH . '/storage';
    protected string $file = '';

    public function __construct()
    {
        $this->file = $this->storageDirectory . '/database.json';
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([
                'orders' => []
            ]));
        }
    }

    public function getCollection(string $key, string $className = DataModel::class): Collection
    {
        $collection = new Collection();
        foreach ($this->getData($key) as $data) {
            $dataObject = new $className($data);
            $collection->add($dataObject);
        }
        return $collection;
    }

    public function saveCollection(string $key, AbstractCollection $collection): bool
    {
        $data = [];
        foreach ($collection as $item) {
            /** @var DataModel $item */
            $data[] = $item->getData();
        }
        return $this->setData($key, $data);
    }

    public function getAllData(): mixed
    {
        return json_decode(file_get_contents($this->file), true);
    }

    public function getData(string $key): mixed
    {
        return $this->getAllData()[$key];
    }

    public function setData(string $key, $data): bool
    {
        $storedData = $this->getAllData();
        $storedData[$key] = $data;
        if ($this->storeData($storedData)) {
            return true;
        }
        return false;
    }

    private function storeData($data): bool|int
    {
        $data = json_encode($data);
        return file_put_contents($this->file, $data);
    }

    public function addDataToKey(string $key, mixed $data)
    {
        $allData = $this->getAllData();
        if (array_key_exists($key, $allData)) {
            array_push($allData[$key], $data);
        }
        $this->storeData($allData);
    }

    public function getOrderCollection(): OrderCollection
    {
        $orders = new OrderCollection();
        $orderData = $this->getData('orders');
        foreach ($orderData as $data) {
            $orders->add(new Order($data));
        }
        return $orders;
    }
}