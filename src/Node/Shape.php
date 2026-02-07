<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Colour\RGBA;
use Innmind\Immutable\Map;

/**
 * @psalm-immutable
 */
final class Shape
{
    /**
     * @param Map<string, string> $attributes
     */
    private function __construct(
        private Map $attributes,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function box(): self
    {
        return self::shape('box');
    }

    /**
     * @psalm-pure
     *
     * @param ?int<3, max> $sides
     * @param ?int<1, max> $peripheries
     */
    #[\NoDiscard]
    public static function polygon(
        ?int $sides = null,
        ?int $peripheries = null,
        ?float $distortion = null,
        ?float $skew = null,
    ): self {
        /** @var Map<string, string> */
        $attributes = Map::of(['shape', 'polygon']);

        if ($sides) {
            $attributes = ($attributes)('sides', (string) $sides);
        }

        if ($peripheries) {
            $attributes = ($attributes)('peripheries', (string) $peripheries);
        }

        if (\is_float($distortion)) {
            $attributes = ($attributes)(
                'distortion',
                \sprintf('%0.1f', $distortion),
            );
        }

        if (\is_float($skew)) {
            $attributes = ($attributes)(
                'skew',
                \sprintf('%0.1f', $skew),
            );
        }

        return new self($attributes);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function ellipse(float $width = .75, float $height = .5): self
    {
        return new self(Map::of(
            ['shape', 'ellipse'],
            ['width', (string) $width],
            ['height', (string) $height],
        ));
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function circle(): self
    {
        return self::shape('circle');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function point(): self
    {
        return self::shape('point');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function egg(): self
    {
        return self::shape('egg');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function triangle(): self
    {
        return self::shape('triangle');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function plaintext(): self
    {
        return self::shape('plaintext');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function diamond(): self
    {
        return self::shape('diamond');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function trapezium(): self
    {
        return self::shape('trapezium');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function parallelogram(): self
    {
        return self::shape('parallelogram');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function house(): self
    {
        return self::shape('house');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function hexagon(): self
    {
        return self::shape('hexagon');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function octagon(): self
    {
        return self::shape('octagon');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function doublecircle(): self
    {
        return self::shape('doublecircle');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function doubleoctagon(): self
    {
        return self::shape('doubleoctagon');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function tripleoctagon(): self
    {
        return self::shape('tripleoctagon');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function invtriangle(): self
    {
        return self::shape('invtriangle');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function invtrapezium(): self
    {
        return self::shape('invtrapezium');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function invhouse(): self
    {
        return self::shape('invhouse');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function Mdiamond(): self
    {
        return self::shape('Mdiamond');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function Msquare(): self
    {
        return self::shape('Msquare');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function Mcircle(): self
    {
        return self::shape('Mcircle');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return self::shape('none');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function record(): self
    {
        return self::shape('record');
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function Mrecord(): self
    {
        return self::shape('Mrecord');
    }

    #[\NoDiscard]
    public function withColor(RGBA $color): self
    {
        return new self(
            ($this->attributes)('color', $color->toString()),
        );
    }

    #[\NoDiscard]
    public function fillWithColor(RGBA $color): self
    {
        return new self(
            ($this->attributes)
                ('style', 'filled')
                ('fillcolor', $color->toString()),
        );
    }

    /**
     * @internal
     *
     * @return Map<string, string>
     */
    #[\NoDiscard]
    public function attributes(): Map
    {
        return $this->attributes;
    }

    /**
     * @psalm-pure
     */
    private static function shape(string $name): self
    {
        return new self(Map::of(['shape', $name]));
    }
}
