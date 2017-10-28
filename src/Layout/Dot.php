<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Node,
    Edge
};
use Innmind\Immutable\Str;

final class Dot
{
    public function __invoke(Node $node): Str
    {
        return $this
            ->visit($node, new Str("digraph G {\n"))
            ->append('}');
    }

    private function visit(Node $node, Str $output): Str
    {
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

        return $node
            ->edges()
            ->map(static function(Edge $edge): Edge {
                if ($edge->to()->edges()->size() === 1) {
                    $edge->to()->edges()->rewind();

                    return $edge->to()->edges()->current();
                }

                return $edge;
            })
            ->reduce(
                $output,
                function(Str $output, Edge $edge): Str {
                    return $this->visit($edge->to(), $output);
                }
            );
    }
}
