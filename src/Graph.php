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
    /**
     * @param T $directed
     * @param Set<Node> $nodes
     * @param Set<self<T>> $clusters
     * @param Map<string, string> $attributes
     */
    private function __construct(
        private string $directed,
        private Name $name,
        private Set $nodes,
        private Set $clusters,
        private Map $attributes,
    ) {
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     *
     * @return self<'directed'>
     */
    #[\NoDiscard]
    public static function directed(string $name = 'G', ?Rankdir $rankdir = null): self
    {
        /** @var Set<Node> */
        $nodes = Set::of();
        /** @var Set<self<'directed'>> */
        $clusters = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();

        if ($rankdir) {
            $attributes = ($attributes)('rankdir', $rankdir->toString());
        }

        return new self('directed', Name::of($name), $nodes, $clusters, $attributes);
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     *
     * @return self<'undirected'>
     */
    #[\NoDiscard]
    public static function undirected(string $name = 'G', ?Rankdir $rankdir = null): self
    {
        /** @var Set<Node> */
        $nodes = Set::of();
        /** @var Set<self<'undirected'>> */
        $clusters = Set::of();
        /** @var Map<string, string> */
        $attributes = Map::of();

        if ($rankdir) {
            $attributes = ($attributes)('rankdir', $rankdir->toString());
        }

        return new self('undirected', Name::of($name), $nodes, $clusters, $attributes);
    }

    #[\NoDiscard]
    public function isDirected(): bool
    {
        return $this->directed === 'directed';
    }

    #[\NoDiscard]
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @param self<T> $cluster
     *
     * @return self<T>
     */
    #[\NoDiscard]
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
    #[\NoDiscard]
    public function clusters(): Set
    {
        return $this->clusters;
    }

    /**
     * @return self<T>
     */
    #[\NoDiscard]
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
    #[\NoDiscard]
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
    #[\NoDiscard]
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
    #[\NoDiscard]
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
    #[\NoDiscard]
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
    #[\NoDiscard]
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
    #[\NoDiscard]
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
    #[\NoDiscard]
    public function attributes(): Map
    {
        return $this->attributes;
    }
}
