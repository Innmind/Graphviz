<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Node,
    Edge,
    Graph,
};
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\{
    Str,
    Maybe,
    Sequence,
};

/**
 * @psalm-immutable
 */
final class Dot
{
    /**
     * @param Maybe<DPI> $dpi
     */
    private function __construct(
        private Maybe $dpi,
    ) {
    }

    public function __invoke(Graph $graph): Content
    {
        $type = $graph->isDirected() ? 'digraph' : 'graph';
        $lines = Sequence::of(Str::of("$type {$graph->name()->toString()} {"))
            ->append($this->dpi())
            ->append($this->attributes($graph))
            ->append($this->clusters($graph))
            ->append($this->edges($graph))
            ->append($this->lonelyRoots($graph))
            ->append($this->styledNodes($graph))
            ->add(Str::of('}'))
            ->map(static fn($line) => Content\Line::of($line));

        return Content::ofLines($lines);
    }

    /**
     * @psalm-pure
     */
    public static function of(?DPI $dpi = null): self
    {
        return new self(Maybe::of($dpi));
    }

    /**
     * @return Sequence<Str>
     */
    private function dpi(): Sequence
    {
        return $this->dpi->match(
            static fn($dpi) => Sequence::of(Str::of(\sprintf(
                '    dpi="%s";',
                $dpi->toInt(),
            ))),
            static fn() => Sequence::of(),
        );
    }

    /**
     * @return Sequence<Str>
     */
    private function attributes(Graph $graph): Sequence
    {
        return $graph
            ->attributes()
            ->map(static fn($key, $value) => Str::of(\sprintf(
                '    %s="%s";',
                $key,
                $value,
            )))
            ->values();
    }

    /**
     * @return Sequence<Str>
     */
    private function clusters(Graph $graph): Sequence
    {
        return Sequence::of(
            ...$graph
                ->clusters()
                ->map(fn($cluster) => $this->cluster($cluster))
                ->toList(),
        )->flatMap(static fn($lines) => $lines);
    }

    /**
     * @return Sequence<Str>
     */
    private function edges(Graph $graph): Sequence
    {
        $type = $graph->isDirected() ? '->' : '--';

        $edges = $graph
            ->nodes()
            ->map(static fn($node) => $node->edges())
            ->flatMap(static fn($nodes) => $nodes)
            ->map(fn($edge) => $this->edge($edge, $type));

        return Sequence::of(...$edges->toList());
    }

    /**
     * @return Sequence<Str>
     */
    private function lonelyRoots(Graph $graph): Sequence
    {
        $lines = $graph
            ->roots()
            ->filter(static fn($node) => $node->attributes()->empty() && $node->edges()->empty()) //styled nodes are rendered below
            ->map(static fn($node) => Str::of('    '.$node->name()->toString().';'))
            ->toList();

        return Sequence::of(...$lines);
    }

    /**
     * @return Sequence<Str>
     */
    private function styledNodes(Graph $graph): Sequence
    {
        $lines = $graph
            ->nodes()
            ->filter(static fn($node) => !$node->attributes()->empty())
            ->map(fn($node) => $this->nodeStyle($node))
            ->toList();

        return Sequence::of(...$lines);
    }

    /**
     * @return Sequence<Str>
     */
    private function cluster(Graph $cluster): Sequence
    {
        $head = Sequence::of(
            Str::of('    subgraph cluster_')
                ->append($cluster->name()->toString())
                ->append(' {'),
        );
        $attributes = $cluster
            ->attributes()
            ->map(static fn($key, $value) => Str::of(\sprintf(
                '        %s="%s"',
                $key,
                $value,
            )))
            ->values();

        return $head
            ->append($attributes)
            ->append($this->edges($cluster))
            ->append($this->lonelyRoots($cluster))
            ->append($this->styledNodes($cluster))
            ->add(Str::of('    }'));
    }

    private function edge(Edge $edge, string $type): Str
    {
        $attributes = '';

        if (!$edge->attributes()->empty()) {
            $attributes = $edge
                ->attributes()
                ->map(static fn($key, $value) => \sprintf(
                    '%s="%s"',
                    $key,
                    $value,
                ))
                ->values();
            $attributes = Str::of(', ')
                ->join($attributes)
                ->prepend(' [')
                ->append(']')
                ->toString();
        }

        return Str::of(\sprintf(
            '    %s %s %s',
            $edge->from()->toString(),
            $type,
            $edge->to()->toString(),
        ))
            ->append($attributes)
            ->append(';');
    }

    private function nodeStyle(Node $node): Str
    {
        $attributes = $node
            ->attributes()
            ->map(static fn($key, $value) => \sprintf(
                '%s="%s"',
                $key,
                $value,
            ))
            ->values();
        $attributes = Str::of(', ')
            ->join($attributes)
            ->prepend(' [')
            ->append(']')
            ->toString();

        return Str::of('    '.$node->name()->toString())
            ->append($attributes)
            ->append(';');
    }
}
