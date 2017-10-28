<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node as NodeInterface,
    Edge,
    Exception\DomainException
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class Node implements NodeInterface
{
    private $name;
    private $edges;

    public function __construct(string $name)
    {
        if (strlen($name) === 0) {
            throw new DomainException;
        }

        $this->name = $name;
        $this->edges = new Set(Edge::class);
    }

    public function name(): string
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
}
