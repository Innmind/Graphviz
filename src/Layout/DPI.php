<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\Exception\DomainException;

final class DPI
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 1) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    /**
     * @throws DomainException
     */
    public static function of(int $value): self
    {
        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
