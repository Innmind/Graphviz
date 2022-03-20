<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

use Innmind\Graphviz\{
    Edge as EdgeInterface,
    Node,
    Attribute\Value,
};
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\Map;

final class Edge implements EdgeInterface
{
    private Node $from;
    private Node $to;
    /** @var Map<string, string> */
    private Map $attributes;

    public function __construct(Node $from, Node $to)
    {
        $this->from = $from;
        $this->to = $to;
        /** @var Map<string, string> */
        $this->attributes = Map::of();
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
        $this->attributes = ($this->attributes)('dir', 'both');
    }

    public function withoutDirection(): void
    {
        $this->attributes = ($this->attributes)('dir', 'none');
    }

    public function shaped(
        Shape $shape,
        Shape $shape2 = null,
        Shape $shape3 = null,
        Shape $shape4 = null,
    ): void {
        $shape = $shape->toString();
        $shape2 = $shape2 ? $shape2->toString() : '';
        $shape3 = $shape3 ? $shape3->toString() : '';
        $shape4 = $shape4 ? $shape4->toString() : '';
        $value = $shape.$shape2.$shape3.$shape4;

        $this->attributes = ($this->attributes)('arrowhead', $value);

        $this->attributes = $this
            ->attributes
            ->get('dir')
            ->filter(static fn($dir) => $dir === 'both')
            ->match(
                fn() => ($this->attributes)('arrowtail', $value),
                fn() => $this->attributes,
            );
    }

    public function displayAs(string $label): void
    {
        $this->attributes = ($this->attributes)(
            'label',
            (new Value($label))->toString(),
        );
    }

    public function useColor(RGBA $color): void
    {
        $this->attributes = ($this->attributes)('color', $color->toString());
    }

    public function target(Url $url): void
    {
        $this->attributes = ($this->attributes)(
            'URL',
            (new Value($url->toString()))->toString(),
        );
    }

    public function dotted(): void
    {
        $this->attributes = ($this->attributes)('style', 'dotted');
    }

    public function bold(): void
    {
        $this->attributes = ($this->attributes)('style', 'bold');
    }

    public function filled(): void
    {
        $this->attributes = ($this->attributes)('style', 'filled');
    }

    public function attributes(): Map
    {
        return $this->attributes;
    }
}
