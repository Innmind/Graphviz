<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\{
    Node\Name,
    Edge\Shape,
    Attribute\Value,
};
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\Map;

/**
 * @psalm-immutable
 */
final class Edge
{
    /**
     * @param Map<string, string> $attributes
     */
    private function __construct(
        private Name $from,
        private Name $to,
        private Map $attributes,
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function between(Name $from, Name $to): self
    {
        /** @var Map<string, string> */
        $attributes = Map::of();

        return new self($from, $to, $attributes);
    }

    public function from(): Name
    {
        return $this->from;
    }

    public function to(): Name
    {
        return $this->to;
    }

    public function asBidirectional(): self
    {
        return $this->with('dir', 'both');
    }

    public function withoutDirection(): self
    {
        return $this->with('dir', 'none');
    }

    public function shaped(
        Shape $shape,
        ?Shape $shape2 = null,
        ?Shape $shape3 = null,
        ?Shape $shape4 = null,
    ): self {
        $shape = $shape->toString();
        $shape2 = $shape2 ? $shape2->toString() : '';
        $shape3 = $shape3 ? $shape3->toString() : '';
        $shape4 = $shape4 ? $shape4->toString() : '';
        $value = $shape.$shape2.$shape3.$shape4;

        $self = $this->with('arrowhead', $value);

        return $self
            ->attributes
            ->get('dir')
            ->filter(static fn($dir) => $dir === 'both')
            ->match(
                static fn() => $self->with('arrowtail', $value),
                static fn() => $self,
            );
    }

    /**
     * @param non-empty-string $label
     */
    public function displayAs(string $label): self
    {
        return $this->with(
            'label',
            Value::of($label)->toString(),
        );
    }

    public function useColor(RGBA $color): self
    {
        return $this->with('color', $color->toString());
    }

    public function target(Url $url): self
    {
        return $this->with(
            'URL',
            Value::of($url->toString())->toString(),
        );
    }

    public function dotted(): self
    {
        return $this->with('style', 'dotted');
    }

    public function bold(): self
    {
        return $this->with('style', 'bold');
    }

    public function filled(): self
    {
        return $this->with('style', 'filled');
    }

    /**
     * @internal
     *
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }

    private function with(string $key, string $value): self
    {
        return new self(
            $this->from,
            $this->to,
            ($this->attributes)($key, $value),
        );
    }
}
