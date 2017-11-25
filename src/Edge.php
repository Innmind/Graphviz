<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Edge\Shape;
use Innmind\Immutable\MapInterface;

interface Edge
{
    public function from(): Node;
    public function to(): Node;
    public function asBidirectional(): self;
    public function withoutDirection(): self;
    public function shaped(
        Shape $shape,
        Shape $shape2 = null,
        Shape $shape3 = null,
        Shape $shape4 = null
    ): self;
    public function displayAs(string $label): self;
    public function hasAttributes(): bool;

    /**
     * @return MapInterface<string, mixed>
     */
    public function attributes(): MapInterface;
}
