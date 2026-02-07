<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

/**
 * @psalm-immutable
 */
enum Shape
{
    case box;
    case crow;
    case curve;
    case icurve;
    case diamond;
    case dot;
    case inv;
    case none;
    case normal;
    case tee;
    case vee;

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->name;
    }
}
