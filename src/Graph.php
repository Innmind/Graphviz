<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Graph\Name,
    Graph\Rankdir,
    Attribute\Value,
    Exception\MixedGraphsNotAllowed,
};
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};

final class Graph
{
    private bool $directed;
    private Name $name;
    /** @var Set<Node> */
    private Set $roots;
    /** @var Set<self> */
    private Set $clusters;
    /** @var Map<string, string> */
    private Map $attributes;

    private function __construct(bool $directed, Name $name, Rankdir $rankdir = null)
    {
        $this->directed = $directed;
        $this->name = $name;
        /** @var Set<Node> */
        $this->roots = Set::of();
        /** @var Set<self> */
        $this->clusters = Set::of();
        /** @var Map<string, string> */
        $this->attributes = Map::of();

        if ($rankdir) {
            $this->attributes = ($this->attributes)('rankdir', $rankdir->toString());
        }
    }

    public static function directed(string $name = 'G', Rankdir $rankdir = null): self
    {
        return new self(true, new Name($name), $rankdir);
    }

    public static function undirected(string $name = 'G', Rankdir $rankdir = null): self
    {
        return new self(false, new Name($name), $rankdir);
    }

    public function isDirected(): bool
    {
        return $this->directed;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function cluster(self $cluster): void
    {
        if ($cluster->isDirected() !== $this->directed) {
            throw new MixedGraphsNotAllowed;
        }

        $this->clusters = ($this->clusters)($cluster);
    }

    /**
     * @return Set<self>
     */
    public function clusters(): Set
    {
        return $this->clusters;
    }

    public function add(Node $node): void
    {
        $this->roots = ($this->roots)($node);
    }

    /**
     * @return Set<Node>
     */
    public function roots(): Set
    {
        return $this->roots;
    }

    /**
     * @return Set<Node>
     */
    public function nodes(): Set
    {
        /** @var Map<string, Node> */
        $map = $this->roots->reduce(
            Map::of(),
            function(Map $nodes, Node $node): Map {
                return $this->accumulateNodes($nodes, $node);
            },
        );

        /** @var Set<Node> */
        return Set::of(...$map->values()->toList());
    }

    public function displayAs(string $label): void
    {
        $this->attributes = ($this->attributes)(
            'label',
            (new Value($label))->toString(),
        );
    }

    public function fillWithColor(RGBA $color): void
    {
        $this->attributes = ($this->attributes)
            ('style', 'filled')
            ('fillcolor', $color->toString());
    }

    public function colorizeBorderWith(RGBA $color): void
    {
        $this->attributes = ($this->attributes)('color', $color->toString());
    }

    public function target(Url $url): void
    {
        $this->attributes = ($this->attributes)(
            'URL',
            (new Value($url->toString()))->toString(),
        );
    }

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }

    private function accumulateNodes(Map $nodes, Node $node): Map
    {
        return $node
            ->edges()
            ->reduce(
                ($nodes)($node->name()->toString(), $node),
                function(Map $nodes, Edge $edge): Map {
                    if ($nodes->values()->contains($edge->to())) {
                        return $nodes;
                    }

                    return $this->accumulateNodes($nodes, $edge->to());
                },
            );
    }
}
