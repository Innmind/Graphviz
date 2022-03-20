<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Edge\Shape;
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\Map;

interface Edge
{
    public function from(): Node;
    public function to(): Node;
    public function asBidirectional(): void;
    public function withoutDirection(): void;
    public function shaped(
        Shape $shape,
        Shape $shape2 = null,
        Shape $shape3 = null,
        Shape $shape4 = null,
    ): void;
    public function displayAs(string $label): void;
    public function useColor(RGBA $color): void;
    public function target(Url $url): void;
    public function dotted(): void;
    public function bold(): void;
    public function filled(): void;
    public function hasAttributes(): bool;

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map;
}
