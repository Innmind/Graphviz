<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

final class Name
{
    private $value;

    public function __construct(string $name)
    {
        $name = new Str($name);

        if (
            $name->length() === 0 ||
            $name->contains('->') ||
            $name->contains('-') ||
            $name->contains("\x00")
        ) {
            throw new DomainException;
        }

        $this->value = (string) $name;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
