<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Graph\Name,
    Exception\MixedGraphsNotAllowed
};
use Innmind\Colour\RGBA;
use Innmind\Immutable\{
    SetInterface,
    Set,
    MapInterface,
    Map
};

final class Graph
{
    private $directed;
    private $name;
    private $roots;
    private $clusters;
    private $attributes;

    private function __construct(bool $directed, Name $name)
    {
        $this->directed = $directed;
        $this->name = $name;
        $this->roots = new Set(Node::class);
        $this->clusters = new Set(self::class);
        $this->attributes = new Map('string', 'string');
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

    public function cluster(Graph $cluster): self
    {
        if ($cluster->isDirected() !== $this->directed) {
            throw new MixedGraphsNotAllowed;
        }

        $this->clusters = $this->clusters->add($cluster);

        return $this;
    }

    /**
     * @return SetInterface<self>
     */
    public function clusters(): SetInterface
    {
        return $this->clusters;
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

    public function displayAs(string $label): self
    {
        $this->attributes = $this->attributes->put('label', $label);

        return $this;
    }

    public function fillWithColor(RGBA $color): self
    {
        $this->attributes = $this
            ->attributes
            ->put('style', 'filled')
            ->put('fillcolor', (string) $color);

        return $this;
    }

    public function colorizeBorderWith(RGBA $color): self
    {
        $this->attributes = $this->attributes->put('color', (string) $color);

        return $this;
    }

    /**
     * @return MapInterface<string, string>
     */
    public function attributes(): MapInterface
    {
        return $this->attributes;
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
