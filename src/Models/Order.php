<?php

namespace Aashan\Workflow\Models;


use Aashan\Workflow\Collections\Order\OrderCollection;
use JetBrains\PhpStorm\Pure;

class Order extends AbstractModel
{
    protected static string $collectionName = 'orders';
    protected static string $idField = 'order_id';
    protected static string $collectionClass = OrderCollection::class;

    #[Pure]
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function getStatus(): string
    {
        return $this->getData('status');
    }

    public function setStatus($status): self
    {
        $this->setData('status', $status);
        return $this;
    }

}
