<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Layout;

use Innmind\Graphviz\Exception\DomainException;

/**
 * @psalm-immutable
 */
final class DPI
{
    /** @var int<1, max> */
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 1) {
            throw new DomainException;
        }

        $this->value = $value;
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
