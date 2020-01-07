<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Graph;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

final class Name
{
    private string $value;

    public function __construct(string $name)
    {
        if (!Str::of($name)->matches('~[a-zA-Z0-9_]+~')) {
            throw new DomainException($name);
        }

        $this->value = $name;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
