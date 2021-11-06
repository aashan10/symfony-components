<?php

namespace Aashan\Workflow\DataModel;


class DataModel
{
    public function __construct(protected array $data = [])
    {
    }

    public function getData(string $key = null): mixed
    {
        if (!$key) {
            return $this->data;
        }
        return $this->data[$key] ?? null;
    }

    public function setData(string $key, string $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }
}