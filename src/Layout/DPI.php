<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\Exception\DomainException;

final class DPI
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
