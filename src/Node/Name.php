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
    /** @var non-empty-string */
    private string $value;

    private function __construct(string $name)
    {
        $name = Str::of($name);

        if (
            $name->empty() ||
            $name->contains('->') ||
            $name->contains('-') ||
            $name->contains('.') ||
            $name->contains("\x00")
        ) {
            throw new DomainException($name->toString());
        }

        /** @var non-empty-string */
        $this->value = $name->toString();
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function of(string $name): self
    {
        return new self($name);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
