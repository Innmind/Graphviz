<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Node\Name,
    Node\Shape,
    Attribute\Value,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
    Maybe,
};

/**
 * @psalm-immutable
 */
final class Node
{
    private Name $name;
    /** @var Set<Edge> */
    private Set $edges;
    /** @var Map<string, string> */
    private Map $attributes;
    /** @var Maybe<Shape> */
    private Maybe $shape;

    /**
     * @param Set<Edge> $edges
     * @param Map<string, string> $attributes
     * @param Maybe<Shape> $shape
     */
    private function __construct(
        Name $name,
        Set $edges,
        Map $attributes,
        Maybe $shape,
    ) {
        $this->name = $name;
        $this->edges = $edges;
        $this->attributes = $attributes;
        $this->shape = $shape;
    }

    /**
     * @psalm-pure
     */
    public static function of(Name $name): self
    {
        /** @var Set<Edge> */
        $edges = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();
        /** @var Maybe<Shape> */
        $shape = Maybe::nothing();

        return new self($name, $edges, $attributes, $shape);
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function named(string $name): self
    {
        return self::of(Name::of($name));
    }

    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @return Set<Edge>
     */
    public function edges(): Set
    {
        return $this->edges;
    }

    /**
     * @param (pure-callable(Edge): Edge)|null $map
     */
    public function linkedTo(Name $node, ?callable $map = null): self
    {
        $map ??= static fn(Edge $edge): Edge => $edge;
        $edge = Edge::between($this->name, $node);

        return new self(
            $this->name,
            ($this->edges)($map($edge)),
            $this->attributes,
            $this->shape,
        );
    }

    public function target(Url $url): self
    {
        return new self(
            $this->name,
            $this->edges,
            ($this->attributes)(
                'URL',
                Value::of($url->toString())->toString(),
            ),
            $this->shape,
        );
    }

    /**
     * @param non-empty-string $label
     */
    public function displayAs(string $label): self
    {
        return new self(
            $this->name,
            $this->edges,
            ($this->attributes)(
                'label',
                Value::of($label)->toString(),
            ),
            $this->shape,
        );
    }

    public function shaped(Shape $shape): self
    {
        return new self(
            $this->name,
            $this->edges,
            $this->attributes,
            Maybe::just($shape),
        );
    }

    /**
     * @internal
     *
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this
            ->shape
            ->map(static fn($shape) => $shape->attributes())
            ->match(
                fn($attributes) => $attributes->merge($this->attributes),
                fn() => $this->attributes,
            );
    }
}
