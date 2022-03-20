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

/**
 * @psalm-immutable
 */
final class Graph
{
    private bool $directed;
    private Name $name;
    /** @var Set<Node> */
    private Set $nodes;
    /** @var Set<self> */
    private Set $clusters;
    /** @var Map<string, string> */
    private Map $attributes;

    /**
     * @param Set<Node> $nodes
     * @param Set<self> $clusters
     * @param Map<string, string> $attributes
     */
    private function __construct(
        bool $directed,
        Name $name,
        Set $nodes,
        Set $clusters,
        Map $attributes,
        Rankdir $rankdir = null,
    ) {
        $this->directed = $directed;
        $this->name = $name;
        $this->nodes = $nodes;
        $this->clusters = $clusters;
        $this->attributes = $attributes;

        if ($rankdir) {
            $this->attributes = ($this->attributes)('rankdir', $rankdir->toString());
        }
    }

    /**
     * @psalm-pure
     */
    public static function directed(string $name = 'G', Rankdir $rankdir = null): self
    {
        /** @var Set<Node> */
        $nodes = Set::of();
        /** @var Set<self> */
        $clusters = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();

        return new self(true, Name::of($name), $nodes, $clusters, $attributes, $rankdir);
    }

    /**
     * @psalm-pure
     */
    public static function undirected(string $name = 'G', Rankdir $rankdir = null): self
    {
        /** @var Set<Node> */
        $nodes = Set::of();
        /** @var Set<self> */
        $clusters = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();

        return new self(false, Name::of($name), $nodes, $clusters, $attributes, $rankdir);
    }

    public function isDirected(): bool
    {
        return $this->directed;
    }

    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @throws MixedGraphsNotAllowed
     */
    public function cluster(self $cluster): self
    {
        if ($cluster->isDirected() !== $this->directed) {
            throw new MixedGraphsNotAllowed;
        }

        return new self(
            $this->directed,
            $this->name,
            $this->nodes,
            ($this->clusters)($cluster),
            $this->attributes,
        );
    }

    /**
     * @return Set<self>
     */
    public function clusters(): Set
    {
        return $this->clusters;
    }

    public function add(Node $node): self
    {
        return new self(
            $this->directed,
            $this->name,
            ($this->nodes)($node),
            $this->clusters,
            $this->attributes,
        );
    }

    /**
     * @return Set<Node>
     */
    public function roots(): Set
    {
        $targeted = $this
            ->nodes
            ->map(static fn($node) => $node->edges())
            ->flatMap(static fn($edges) => $edges)
            ->map(static fn($edge) => $edge->to()->toString());

        return $this->nodes->filter(
            static fn($node) => !$targeted->contains($node->name()->toString()),
        );
    }

    /**
     * @return Set<Node>
     */
    public function nodes(): Set
    {
        return $this->nodes;
    }

    public function displayAs(string $label): self
    {
        return new self(
            $this->directed,
            $this->name,
            $this->nodes,
            $this->clusters,
            ($this->attributes)(
                'label',
                Value::of($label)->toString(),
            ),
        );
    }

    public function fillWithColor(RGBA $color): self
    {
        return new self(
            $this->directed,
            $this->name,
            $this->nodes,
            $this->clusters,
            ($this->attributes)
                ('style', 'filled')
                ('fillcolor', $color->toString()),
        );
    }

    public function colorizeBorderWith(RGBA $color): self
    {
        return new self(
            $this->directed,
            $this->name,
            $this->nodes,
            $this->clusters,
            ($this->attributes)('color', $color->toString()),
        );
    }

    public function target(Url $url): self
    {
        return new self(
            $this->directed,
            $this->name,
            $this->nodes,
            $this->clusters,
            ($this->attributes)(
                'URL',
                Value::of($url->toString())->toString(),
            ),
        );
    }

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }
}
