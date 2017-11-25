<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Graph\Name;
use Innmind\Immutable\{
    SetInterface,
    Set,
    Map
};

final class Graph
{
    private $directed;
    private $name;
    private $roots;

    private function __construct(bool $directed, Name $name)
    {
        $this->directed = $directed;
        $this->name = $name;
        $this->roots = new Set(Node::class);
    }

    public static function directed(string $name = 'G'): self
    {
        return new self(true, new Name($name));
    }

    public static function undirected(string $name = 'G'): self
    {
        return new self(false, new Name($name));
    }

    public function isDirected(): bool
    {
        return $this->directed;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function add(Node $node): self
    {
        $this->roots = $this->roots->add($node);

        return $this;
    }

    /**
     * @return SetInterface<Node>
     */
    public function roots(): SetInterface
    {
        return $this->roots;
    }

    /**
     * @return SetInterface<Node>
     */
    public function nodes(): SetInterface
    {
        $map = $this->roots->reduce(
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
