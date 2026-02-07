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
    /**
     * @param non-empty-string $value
     */
    private function __construct(
        private string $value,
    ) {
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     *
     * @throws DomainException
     */
    #[\NoDiscard]
    public static function of(string $name): self
    {
        if (!Str::of($name)->matches('~[a-zA-Z0-9_]+~')) {
            throw new DomainException($name);
        }

        return new self($name);
    }

    /**
     * @return non-empty-string
     */
    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value;
    }
}
