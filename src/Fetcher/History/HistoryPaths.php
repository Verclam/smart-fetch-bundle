<?php

namespace Verclam\SmartFetchBundle\Fetcher\History;

use Countable;
use Iterator;
use Verclam\SmartFetchBundle\Fetcher\TreeBuilder\Component\Component;

/**
 * Class PropertyPaths used to store the actual position in the tree
 * @package Verclam\SmartFetchBundle\Fetcher\PropertyPaths
 * @implements Iterator<int, Component>
 * @implements Countable
 */
class HistoryPaths implements Iterator, Countable
{

    private int $position = 0;
    /**
     * @var Component[]
     */
    private array $propertyPaths = [];

    public function add(Component $component): static
    {
        $component->setIsInitialized(true);
        $this->propertyPaths[] = $component;
        return $this;
    }

    public function removeLast(): static
    {
        array_pop($this->propertyPaths);
        return $this;
    }

    public function removeFirst(): static
    {
        if(count($this->propertyPaths) > 0){
            unset($this->propertyPaths[0]);
        }
        return $this;
    }

    public function reverse(): HistoryPaths
    {
        $this->propertyPaths = array_reverse($this->propertyPaths);
        return $this;
    }

    public function getAll(): array
    {
        return $this->propertyPaths;
    }

    public function get(int $index): Component
    {
        return $this->propertyPaths[$index];
    }

    public function has(int $key): bool
    {
        return isset($this->propertyPaths[$key]);
    }

    #[\ReturnTypeWillChange]
    public function current(): Component
    {
        return $this->propertyPaths[$this->position];
    }

    public function next(): void
    {
        --$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->propertyPaths[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = $this->count() - 1;
    }

    public function count(): int
    {
        return count($this->propertyPaths);
    }

}