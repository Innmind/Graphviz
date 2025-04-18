<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Graph\Name,
    Graph\Rankdir,
    Attribute\Value,
};
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};

/**
 * @psalm-immutable
 *
 * @template T of 'directed'|'undirected'
 */
final class Graph
{
    /** @var T */
    private string $directed;
    private Name $name;
    /** @var Set<Node> */
    private Set $nodes;
    /** @var Set<self<T>> */
    private Set $clusters;
    /** @var Map<string, string> */
    private Map $attributes;

    /**
     * @param T $directed
     * @param Set<Node> $nodes
     * @param Set<self<T>> $clusters
     * @param Map<string, string> $attributes
     */
    private function __construct(
        string $directed,
        Name $name,
        Set $nodes,
        Set $clusters,
        Map $attributes,
        ?Rankdir $rankdir = null,
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
     *
     * @param non-empty-string $name
     *
     * @return self<'directed'>
     */
    public static function directed(string $name = 'G', ?Rankdir $rankdir = null): self
    {
        /** @var Set<Node> */
        $nodes = Set::of();
        /** @var Set<self<'directed'>> */
        $clusters = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();

        return new self('directed', Name::of($name), $nodes, $clusters, $attributes, $rankdir);
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     *
     * @return self<'undirected'>
     */
    public static function undirected(string $name = 'G', ?Rankdir $rankdir = null): self
    {
        /** @var Set<Node> */
        $nodes = Set::of();
        /** @var Set<self<'undirected'>> */
        $clusters = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();

        return new self('undirected', Name::of($name), $nodes, $clusters, $attributes, $rankdir);
    }

    public function isDirected(): bool
    {
        return $this->directed === 'directed';
    }

    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @param self<T> $cluster
     *
     * @return self<T>
     */
    public function cluster(self $cluster): self
    {
        return new self(
            $this->directed,
            $this->name,
            $this->nodes,
            ($this->clusters)($cluster),
            $this->attributes,
        );
    }

    /**
     * @return Set<self<T>>
     */
    public function clusters(): Set
    {
        return $this->clusters;
    }

    /**
     * @return self<T>
     */
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
     * @internal
     *
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
     * @internal
     *
     * @return Set<Node>
     */
    public function nodes(): Set
    {
        return $this->nodes;
    }

    /**
     *
     * @param non-empty-string $label
     *
     * @return self<T>
     */
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

    /**
     * @return self<T>
     */
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

    /**
     * @return self<T>
     */
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

    /**
     * @return self<T>
     */
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
     * @internal
     *
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }
}
