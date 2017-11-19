<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node as NodeInterface,
    Edge,
    Exception\DomainException
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    SetInterface,
    Set,
    MapInterface,
    Map
};

final class Node implements NodeInterface
{
    private $name;
    private $edges;
    private $attributes;
    private $shape;

    public function __construct(Name $name)
    {
        $this->name = $name;
        $this->edges = new Set(Edge::class);
        $this->attributes = new Map('string', 'mixed');
        $this->shape = Shape::box();
    }

    public function name(): Name
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function edges(): SetInterface
    {
        return $this->edges;
    }

    public function linkedTo(NodeInterface $node): Edge
    {
        $edge = new Edge\Edge($this, $node);
        $this->edges = $this->edges->add($edge);

        return $edge;
    }

    public function target(UrlInterface $url): self
    {
        $this->attributes = $this->attributes->put('target', $url);

        return $this;
    }

    public function displayAs(string $label): self
    {
        $this->attributes = $this->attributes->put('label', $label);

        return $this;
    }

    public function shaped(Shape $shape): self
    {
        $this->shape = $shape;

        return $this;
    }

    public function hasCustomShape(): bool
    {
        return (string) $this->shape !== (string) Shape::box();
    }

    public function shape(): Shape
    {
        return $this->shape;
    }

    public function hasAttributes(): bool
    {
        return $this->attributes->count() > 0;
    }

    /**
     * @return MapInterface<string, mixed>
     */
    public function attributes(): MapInterface
    {
        return $this->attributes;
    }
}
