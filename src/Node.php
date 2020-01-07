<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Node\{
    Name,
    Shape,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    SetInterface,
    MapInterface,
};

interface Node
{
    public function name(): Name;

    /**
     * @return SetInterface<Edge>
     */
    public function edges(): SetInterface;
    public function linkedTo(self $node): Edge;
    public function target(UrlInterface $url): void;
    public function displayAs(string $label): void;
    public function shaped(Shape $shape): void;
    public function hasAttributes(): bool;

    /**
     * @return MapInterface<string, string>
     */
    public function attributes(): MapInterface;
}
