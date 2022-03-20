<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

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

    /**
     * @psalm-pure
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
