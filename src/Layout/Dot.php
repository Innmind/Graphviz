<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Node,
    Edge
};
use Innmind\Immutable\{
    Str,
    Set,
    Sequence
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
            $output = $this->visit($node, $output);
            $attributes = $this->attributes($node, new Str(''));

            if ($attributes->length() > 0) {
                $output = $output
                    ->append("\n")
                    ->append((string) $attributes);
            }
        } finally {
            $this->rendered = null;
        }

        return $output->append('}');
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
            );
        $this->rendered = $this->rendered->add($node);

        return $node
            ->edges()
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

    private function attributes(Node $node, Str $output): Str
    {
        if ($node->hasAttributes()) {
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

            $output = $output
                ->append('    '.$node->name())
                ->append($attributes)
                ->append(";\n");
        }

        return $node
            ->edges()
            ->reduce(
                $output,
                function(Str $output, Edge $edge): Str {
                    return $this->attributes($edge->to(), $output);
                }
            );
    }
}
