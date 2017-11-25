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
    private $size;

    public function __construct(Size $size = null)
    {
        $this->size = $size;
    }

    public function __invoke(Graph $graph): Str
    {
        $type = $graph->isDirected() ? 'digraph' : 'graph';
        $output = new Str("$type G {\n");

        if ($this->size) {
            $output = $output
                ->append(sprintf(
                    '    size = "%s,%s";',
                    $this->size->width(),
                    $this->size->height()
                ))
                ->append("\n");
        }

        $output = $graph
            ->nodes()
            ->reduce(
                new Set(Edge::class),
                static function(Set $edges, Node $node): Set {
                    return $edges->merge($node->edges());
                }
            )
            ->reduce(
                $output,
                function(Str $output, Edge $edge): Str {
                    return $this->renderEdge($output, $edge);
                }
            );
        $output = $graph
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
        $output = $graph
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

        return $output->append('}');
    }

    private function renderEdge(Str $output, Edge $edge): Str
    {
        $attributes = '';

        if ($edge->hasAttributes()) {
            $attributes = (string) $edge
                ->attributes()
                ->reduce(
                    new Str(''),
                    static function(Str $attributes, string $key, string $value): Str {
                        return $attributes->append(sprintf(
                            '%s="%s"',
                            $key,
                            $value
                        ));
                    }
                )
                ->prepend(' [')
                ->append(']');
        }

        return $output
            ->append(sprintf(
                '    %s -> %s',
                $edge->from()->name(),
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
