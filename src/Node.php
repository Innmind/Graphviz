<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Node\Name,
    Node\Shape,
    Attribute\Value,
    Exception\DomainException,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
    Maybe,
};

final class Node
{
    private Name $name;
    /** @var Set<Edge> */
    private Set $edges;
    /** @var Map<string, string> */
    private Map $attributes;
    /** @var Maybe<Shape> */
    private Maybe $shape;

    private function __construct(Name $name)
    {
        $this->name = $name;
        /** @var Set<Edge> */
        $this->edges = Set::of();
        /** @var Map<string, string> */
        $this->attributes = Map::of();
        /** @var Maybe<Shape> */
        $this->shape = Maybe::nothing();
    }

    public static function of(Name $name): self
    {
        return new self($name);
    }

    public static function named(string $name): self
    {
        return new self(Name::of($name));
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
     * @param (callable(Edge): Edge)|null $map
     */
    public function linkedTo(self $node, callable $map = null): void
    {
        $map ??= static fn(Edge $edge): Edge => $edge;
        $edge = Edge::between($this->name, $node->name());
        $this->edges = ($this->edges)($map($edge));
    }

    public function target(Url $url): void
    {
        $this->attributes = ($this->attributes)(
            'URL',
            Value::of($url->toString())->toString(),
        );
    }

    public function displayAs(string $label): void
    {
        $this->attributes = ($this->attributes)(
            'label',
            Value::of($label)->toString(),
        );
    }

    public function shaped(Shape $shape): void
    {
        $this->shape = Maybe::just($shape);
    }

    /**
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
