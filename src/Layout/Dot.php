<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Node,
    Edge,
    Graph
};
use Innmind\Immutable\{
    Str,
    Set,
    Sequence
};

final class Dot
{
    private $dpi;

    public function __construct(DPI $dpi = null)
    {
        $this->dpi = $dpi;
    }

    public function __invoke(Graph $graph): Str
    {
        $type = $graph->isDirected() ? 'digraph' : 'graph';
        $output = new Str("$type {$graph->name()} {\n");

        $output = $this->renderDPI($output);
        $output = $this->renderAttributes($output, $graph);
        $output = $this->renderClusters($output, $graph);
        $output = $this->renderEdges($output, $graph);
        $output = $this->renderLonelyRoots($output, $graph);
        $output = $this->renderStyledNodes($output, $graph);

        return $output->append('}');
    }

    private function renderDPI(Str $output): Str
    {
        if (!$this->dpi instanceof DPI) {
            return $output;
        }

        return $output
            ->append(sprintf(
                '    dpi="%s";',
                $this->dpi->toInt()
            ))
            ->append("\n");
    }

    private function renderAttributes(Str $output, Graph $graph): Str
    {
        return $graph
            ->attributes()
            ->reduce(
                $output,
                static function(Str $output, string $key, string $value): Str {
                    return $output
                        ->append(sprintf(
                            '    %s="%s";',
                            $key,
                            $value
                        ))
                        ->append("\n");
                }
            );
    }

    private function renderClusters(Str $output, Graph $graph): Str
    {
        return $graph
            ->clusters()
            ->reduce(
                $output,
                function(Str $output, Graph $cluster): Str {
                    return $this->renderCluster($output, $cluster);
                }
            );
    }

    private function renderEdges(Str $output, Graph $graph): Str
    {
        $type = $graph->isDirected() ? '->' : '--';

        return $graph
            ->nodes()
            ->reduce(
                new Set(Edge::class),
                static function(Set $edges, Node $node): Set {
                    return $edges->merge($node->edges());
                }
            )
            ->reduce(
                $output,
                function(Str $output, Edge $edge) use ($type): Str {
                    return $this->renderEdge($output, $edge, $type);
                }
            );
    }

    private function renderLonelyRoots(Str $output, Graph $graph): Str
    {
        return $graph
            ->roots()
            ->filter(static function(Node $node): bool {
                //styled nodes are rendered below
                return !$node->hasAttributes() && $node->edges()->size() === 0;
            })
            ->reduce(
                $output,
                static function(Str $output, Node $node): Str {
                    return $output
                        ->append('    '.$node->name())
                        ->append(";\n");
                }
            );
    }

    private function renderStyledNodes(Str $output, Graph $graph): Str
    {
        return $graph
            ->nodes()
            ->filter(static function(Node $node): bool {
                return $node->hasAttributes();
            })
            ->reduce(
                $output,
                function(Str $output, Node $node): Str {
                    return $this->renderNodeStyle($output, $node);
                }
            );
    }

    public function renderCluster(Str $output, Graph $cluster): Str
    {
        $output = $output
            ->append('    subgraph cluster_')
            ->append((string) $cluster->name())
            ->append(" {\n");

        $output = $cluster
            ->attributes()
            ->reduce(
                $output,
                static function(Str $output, string $key, string $value): Str {
                    return $output->append(sprintf(
                        "        %s=\"%s\"\n",
                        $key,
                        $value
                    ));
                }
            );

        $output = $this->renderEdges($output, $cluster);
        $output = $this->renderLonelyRoots($output, $cluster);
        $output = $this->renderStyledNodes($output, $cluster);

        return $output->append("    }\n");
    }

    private function renderEdge(Str $output, Edge $edge, string $type): Str
    {
        $attributes = '';

        if ($edge->hasAttributes()) {
            $attributes = (string) $edge
                ->attributes()
                ->map(static function(string $key, string $value): string {
                    return sprintf(
                        '%s="%s"',
                        $key,
                        $value
                    );
                })
                ->join(', ')
                ->prepend(' [')
                ->append(']');
        }

        return $output
            ->append(sprintf(
                '    %s %s %s',
                $edge->from()->name(),
                $type,
                $edge->to()->name()
            ))
            ->append($attributes)
            ->append(";\n");
    }

    private function renderNodeStyle(Str $output, Node $node): Str
    {
        $attributes = (string) $node
            ->attributes()
            ->map(static function(string $key, string $value): string {
                return sprintf(
                    '%s="%s"',
                    $key,
                    $value
                );
            })
            ->join(', ')
            ->prepend(' [')
            ->append(']');

        return $output
            ->append('    '.$node->name())
            ->append($attributes)
            ->append(";\n");
    }
}
