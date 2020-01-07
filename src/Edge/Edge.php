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

    public function asBidirectional(): void
    {
        $this->attributes = $this->attributes->put('dir', 'both');
    }

    public function withoutDirection(): void
    {
        $this->attributes = $this->attributes->put('dir', 'none');
    }

    public function shaped(
        Shape $shape,
        Shape $shape2 = null,
        Shape $shape3 = null,
        Shape $shape4 = null
    ): void {
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
    }

    public function displayAs(string $label): void
    {
        $this->attributes = $this->attributes->put(
            'label',
            (new Value($label))->toString(),
        );
    }

    public function useColor(RGBA $color): void
    {
        $this->attributes = $this->attributes->put('color', (string) $color);
    }

    public function target(UrlInterface $url): void
    {
        $this->attributes = $this->attributes->put(
            'URL',
            (new Value((string) $url))->toString(),
        );
    }

    public function dotted(): void
    {
        $this->attributes = $this->attributes->put('style', 'dotted');
    }

    public function bold(): void
    {
        $this->attributes = $this->attributes->put('style', 'bold');
    }

    public function filled(): void
    {
        $this->attributes = $this->attributes->put('style', 'filled');
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
