<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Edge\Shape,
    Attribute\Value,
};
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\Map;

final class Edge
{
    private Node\Name $from;
    private Node $to;
    /** @var Map<string, string> */
    private Map $attributes;

    private function __construct(Node\Name $from, Node $to)
    {
        $this->from = $from;
        $this->to = $to;
        /** @var Map<string, string> */
        $this->attributes = Map::of();
    }

    public static function between(Node\Name $from, Node $to): self
    {
        return new self($from, $to);
    }

    public function from(): Node\Name
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
            Value::of($label)->toString(),
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
            Value::of($url->toString())->toString(),
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

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }
}
