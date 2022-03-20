<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Attribute;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

final class Value
{
    private string $value;

    private function __construct(string $value)
    {
        if (Str::of($value)->contains("\x00")) {
            throw new DomainException($value);
        }

        $this->value = $value;
    }

    /**
     * @throws DomainException
     */
    public static function of(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
