<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Graph;

/**
 * @psalm-immutable
 */
enum Rankdir
{
    case leftToRight;
    case topToBottom;

    #[\NoDiscard]
    public function toString(): string
    {
        return match ($this) {
            self::leftToRight => 'LR',
            self::topToBottom => 'TB',
        };
    }
}
