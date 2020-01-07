<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Node\{
    Name,
    Shape,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};

interface Node
{
    public function name(): Name;

    /**
     * @return Set<Edge>
     */
    public function edges(): Set;
    public function linkedTo(self $node): Edge;
    public function target(Url $url): void;
    public function displayAs(string $label): void;
    public function shaped(Shape $shape): void;
    public function hasAttributes(): bool;

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map;
}
