<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\Exception\DomainException;

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
     *
     * @throws DomainException
     */
    public static function of(int $value): self
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if ($value < 1) {
            throw new DomainException;
        }

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
