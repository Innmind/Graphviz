<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\Exception\DomainException;

final class Size
{
    private $width;
    private $height;

    public function __construct(int $width, int $height)
    {
        if (min($width, $height) < 1) {
            throw new DomainException;
        }

        $this->width = $width;
        $this->height = $height;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }
}
