<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node as NodeInterface,
    Edge,
    Attribute\Value,
    Exception\DomainException,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    SetInterface,
    Set,
    MapInterface,
    Map,
};

final class Node implements NodeInterface
{
    private Name $name;
    private SetInterface $edges;
    private MapInterface $attributes;
    private ?Shape $shape = null;

    public function __construct(Name $name)
    {
        $this->name = $name;
        $this->edges = new Set(Edge::class);
        $this->attributes = new Map('string', 'string');
    }

    public static function named(string $name): self
    {
        return new self(new Name($name));
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

    public function target(UrlInterface $url): void
    {
        $this->attributes = $this->attributes->put(
            'URL',
            (new Value((string) $url))->toString(),
        );
    }

    public function displayAs(string $label): void
    {
        $this->attributes = $this->attributes->put(
            'label',
            (new Value($label))->toString(),
        );
    }

    public function shaped(Shape $shape): void
    {
        $this->shape = $shape;
    }

    public function hasAttributes(): bool
    {
        if ($this->shape instanceof Shape) {
            return true;
        }

        return $this->attributes->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function attributes(): MapInterface
    {
        $attributes = $this->attributes;

        if ($this->shape instanceof Shape) {
            $attributes = $this->shape->attributes()->merge($attributes);
        }

        return $attributes;
    }
}
