<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Attribute;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

final class Value
{
    private string $value;

    public function __construct(string $value)
    {
        if ((new Str($value))->contains("\x00")) {
            throw new DomainException($value);
        }

        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
