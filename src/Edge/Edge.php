<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

use Innmind\Graphviz\{
    Edge as EdgeInterface,
    Node
};
use Innmind\Colour\RGBA;
use Innmind\Immutable\{
    MapInterface,
    Map
};

final class Edge implements EdgeInterface
{
    private $from;
    private $to;
    private $attributes;

    public function __construct(Node $from, Node $to)
    {
        $this->from = $from;
        $this->to = $to;
        $this->attributes = new Map('string', 'string');
    }

    public function from(): Node
    {
        return $this->from;
    }

    public function to(): Node
    {
        return $this->to;
    }

    public function asBidirectional(): EdgeInterface
    {
        $this->attributes = $this->attributes->put('dir', 'both');

        return $this;
    }

    public function withoutDirection(): EdgeInterface
    {
        $this->attributes = $this->attributes->put('dir', 'none');

        return $this;
    }

    public function shaped(
        Shape $shape,
        Shape $shape2 = null,
        Shape $shape3 = null,
        Shape $shape4 = null
    ): EdgeInterface {
        $value = $shape.$shape2.$shape3.$shape4;

        $this->attributes = $this->attributes->put('arrowhead', $value);

        if (
            $this->attributes->contains('dir') &&
            $this->attributes->get('dir') === 'both'
        ) {
            $this->attributes = $this->attributes->put('arrowtail', $value);
        }

        return $this;
    }

    public function displayAs(string $label): EdgeInterface
    {
        $this->attributes = $this->attributes->put('label', $label);

        return $this;
    }

    public function useColor(RGBA $color): EdgeInterface
    {
        $this->attributes = $this->attributes->put('color', (string) $color);

        return $this;
    }

    public function hasAttributes(): bool
    {
        return $this->attributes->size() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function attributes(): MapInterface
    {
        return $this->attributes;
    }
}
