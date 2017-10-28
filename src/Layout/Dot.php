<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Node,
    Edge
};
use Innmind\Immutable\{
    Str,
    Set
};

final class Dot
{
    private $size;
    private $rendered;

    public function __construct(Size $size = null)
    {
        $this->size = $size;
    }

    public function __invoke(Node $node): Str
    {
        $output = new Str("digraph G {\n");

        if ($this->size) {
            $output = $output
                ->append(sprintf(
                    '    size = "%s,%s";',
                    $this->size->width(),
                    $this->size->height()
                ))
                ->append("\n");
        }

        $this->rendered = new Set(Node::class);

        try {
            $output = $this
                ->visit($node, $output)
                ->append('}');
        } finally {
            $this->rendered = null;
        }

        return $output;
    }

    private function visit(Node $node, Str $output): Str
    {
        if (
            $node->edges()->size() === 0 &&
            !$this->rendered->contains($node)
        ) {
            return $output
                ->append('    '.$node->name())
                ->append(";\n");
        }

        $output = $node
            ->edges()
            ->reduce(
                $output,
                static function(Str $output, Edge $edge): Str {
                    $output = $output->append(sprintf(
                        '    %s -> %s',
                        $edge->from()->name(),
                        $edge->to()->name()
                    ));

                    if ($edge->to()->edges()->size() === 1) {
                        $edge->to()->edges()->rewind();
                        $output = $output->append(' -> '.$edge->to()->edges()->current()->to()->name());
                    }

                    return $output->append(";\n");
                }
            );
        $this->rendered = $this->rendered->add($node);

        return $node
            ->edges()
            ->map(static function(Edge $edge): Edge {
                if ($edge->to()->edges()->size() === 1) {
                    $edge->to()->edges()->rewind();

                    return $edge->to()->edges()->current();
                }

                return $edge;
            })
            ->foreach(function(Edge $edge): void {
                $this->rendered = $this->rendered->add($edge->to());
            })
            ->reduce(
                $output,
                function(Str $output, Edge $edge): Str {
                    return $this->visit($edge->to(), $output);
                }
            );
    }
}
