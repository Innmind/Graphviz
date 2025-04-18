<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Graph;

use Innmind\Graphviz\Exception\DomainException;
use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Name
{
    private string $value;

    private function __construct(string $name)
    {
        if (!Str::of($name)->matches('~[a-zA-Z0-9_]+~')) {
            throw new DomainException($name);
        }

        $this->value = $name;
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     *
     * @throws DomainException
     */
    public static function of(string $name): self
    {
        return new self($name);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
