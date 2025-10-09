<?php

namespace App\DependencyInjection\Collection;

/**
 * @template Tkey
 * @template Tval
 */
abstract class Collection
{
    /**
     * @var array<Tkey,Tval>
     */
    protected array $items = [];

    /**
     * @param Tkey $key
     * @param Tval $item
     */
    public function add($key, $item): void
    {
        $this->items[$key] = $item;
    }

    /**
     * @return array<Tkey,Tval>
     */
    public function toArray(): array
    {
        return $this->items;
    }
}
