<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

use Innmind\Graphviz\{
    Edge as EdgeInterface,
    Node,
    Attribute\Value,
};
use Innmind\Colour\RGBA;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class Edge implements EdgeInterface
{
    private Node $from;
    private Node $to;
    private MapInterface $attributes;

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
        $shape = $shape->toString();
        $shape2 = $shape2 ? $shape2->toString() : '';
        $shape3 = $shape3 ? $shape3->toString() : '';
        $shape4 = $shape4 ? $shape4->toString() : '';
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
        $this->attributes = $this->attributes->put(
            'label',
            (new Value($label))->toString(),
        );

        return $this;
    }

    public function useColor(RGBA $color): EdgeInterface
    {
        $this->attributes = $this->attributes->put('color', (string) $color);

        return $this;
    }

    public function target(UrlInterface $url): EdgeInterface
    {
        $this->attributes = $this->attributes->put(
            'URL',
            (new Value((string) $url))->toString(),
        );

        return $this;
    }

    public function dotted(): EdgeInterface
    {
        $this->attributes = $this->attributes->put('style', 'dotted');

        return $this;
    }

    public function bold(): EdgeInterface
    {
        $this->attributes = $this->attributes->put('style', 'bold');

        return $this;
    }

    public function filled(): EdgeInterface
    {
        $this->attributes = $this->attributes->put('style', 'filled');

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
