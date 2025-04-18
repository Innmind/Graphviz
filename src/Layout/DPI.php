<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

/**
 * @psalm-immutable
 */
final class DPI
{
    /**
     * @param int<1, max> $value
     */
    private function __construct(
        private int $value,
    ) {
    }

    /**
     * @psalm-pure
     *
     * @param int<1, max> $value
     */
    public static function of(int $value): self
    {
        return new self($value);
    }

    /**
     * @return int<1, max>
     */
    public function toInt(): int
    {
        return $this->value;
    }
}
