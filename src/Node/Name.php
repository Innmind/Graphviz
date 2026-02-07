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
     */
    #[\NoDiscard]
    public static function of(string $name): self
    {
        $str = Str::of($name);

        if (
            $str->contains('->') ||
            $str->contains('-') ||
            $str->contains('.') ||
            $str->contains("\x00")
        ) {
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
