<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node as NodeInterface,
    Edge,
    Attribute\Value,
    Exception\DomainException,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
    Maybe,
};

final class Node implements NodeInterface
{
    private Name $name;
    /** @var Set<Edge> */
    private Set $edges;
    /** @var Map<string, string> */
    private Map $attributes;
    /** @var Maybe<Shape> */
    private Maybe $shape;

    public function __construct(Name $name)
    {
        $this->name = $name;
        /** @var Set<Edge> */
        $this->edges = Set::of();
        /** @var Map<string, string> */
        $this->attributes = Map::of();
        /** @var Maybe<Shape> */
        $this->shape = Maybe::nothing();
    }

    public static function named(string $name): self
    {
        return new self(new Name($name));
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function edges(): Set
    {
        return $this->edges;
    }

    public function linkedTo(NodeInterface $node): Edge
    {
        $edge = new Edge\Edge($this, $node);
        $this->edges = ($this->edges)($edge);

        return $edge;
    }

    public function target(Url $url): void
    {
        $this->attributes = ($this->attributes)(
            'URL',
            (new Value($url->toString()))->toString(),
        );
    }

    public function displayAs(string $label): void
    {
        $this->attributes = ($this->attributes)(
            'label',
            (new Value($label))->toString(),
        );
    }

    public function shaped(Shape $shape): void
    {
        $this->shape = Maybe::just($shape);
    }

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
