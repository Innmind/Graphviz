<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Attribute;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

/**
 * @internal
 * @psalm-immutable
 */
final class Value
{
    private function __construct(
        private string $value,
    ) {
    }

    /**
     * @psalm-pure
     *
     * @throws DomainException
     */
    public static function of(string $value): self
    {
        if (Str::of($value)->contains("\x00")) {
            throw new DomainException($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
