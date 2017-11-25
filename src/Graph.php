<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Immutable\{
    SetInterface,
    Set,
    Map
};

final class Graph
{
    private $directed;
    private $nodes;

    private function __construct(bool $directed)
    {
        $this->directed = $directed;
        $this->nodes = new Set(Node::class);
    }

    public static function directed(): self
    {
        return new self(true);
    }

    public static function undirected(): self
    {
        return new self(false);
    }

    public function isDirected(): bool
    {
        return $this->directed;
    }

    public function add(Node $node): self
    {
        $this->nodes = $this->nodes->add($node);

        return $this;
    }

    /**
     * @return SetInterface<Node>
     */
    public function nodes(): SetInterface
    {
        $map = $this->nodes->reduce(
            new Map('string', Node::class),
            function(Map $nodes, Node $node): Map {
                return $this->accumulateNodes($nodes, $node);
            }
        );

        return Set::of(Node::class, ...$map->values());
    }

    private function accumulateNodes(Map $nodes, Node $node): Map
    {
        return $node
            ->edges()
            ->reduce(
                $nodes->put((string) $node->name(), $node),
                function(Map $nodes, Edge $edge): Map {
                    return $this->accumulateNodes($nodes, $edge->to());
                }
            );
    }
}
