<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Graph;

use Innmind\Graphviz\{
    Graph as GraphInterface,
    Node,
    Edge,
    Attribute\Value,
    Exception\MixedGraphsNotAllowed,
};
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};
use function Innmind\Immutable\unwrap;

final class Graph implements GraphInterface
{
    private bool $directed;
    private Name $name;
    /** @var Set<Node> */
    private Set $roots;
    /** @var Set<GraphInterface> */
    private Set $clusters;
    /** @var Map<string, string> */
    private Map $attributes;

    private function __construct(bool $directed, Name $name, Rankdir $rankdir = null)
    {
        $this->directed = $directed;
        $this->name = $name;
        /** @var Set<Node> */
        $this->roots = Set::of(Node::class);
        /** @var Set<GraphInterface> */
        $this->clusters = Set::of(GraphInterface::class);
        /** @var Map<string, string> */
        $this->attributes = Map::of('string', 'string');

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

    public function cluster(GraphInterface $cluster): void
    {
        if ($cluster->isDirected() !== $this->directed) {
            throw new MixedGraphsNotAllowed;
        }

        $this->clusters = ($this->clusters)($cluster);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function roots(): Set
    {
        return $this->roots;
    }

    /**
     * {@inheritdoc}
     */
    public function nodes(): Set
    {
        $map = $this->roots->reduce(
            Map::of('string', Node::class),
            function(Map $nodes, Node $node): Map {
                return $this->accumulateNodes($nodes, $node);
            }
        );

        /** @var Set<Node> */
        return Set::of(Node::class, ...unwrap($map->values()));
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
     * {@inheritdoc}
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
                }
            );
    }
}
