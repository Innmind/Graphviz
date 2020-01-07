<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

final class Name
{
    private string $value;

    public function __construct(string $name)
    {
        $name = Str::of($name);

        if (
            $name->length() === 0 ||
            $name->contains('->') ||
            $name->contains('-') ||
            $name->contains('.') ||
            $name->contains("\x00")
        ) {
            throw new DomainException($name->toString());
        }

        $this->value = $name->toString();
    }

    public function toString(): string
    {
        return $this->value;
    }
}
