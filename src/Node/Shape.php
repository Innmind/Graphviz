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
    /** @var Map<string, string> */
    private Map $attributes;

    /**
     * @param Map<string, string> $attributes
     */
    private function __construct(Map $attributes)
    {
        $this->attributes = $attributes;
    }

    public static function box(): self
    {
        return new self(Map::of(['shape', 'box']));
    }

    public static function polygon(
        int $sides = null,
        int $peripheries = null,
        float $distortion = null,
        float $skew = null,
    ): self {
        /** @var Map<string, string> */
        $attributes = Map::of(['shape', 'polygon']);

        if ($sides) {
            $attributes = ($attributes)('sides', (string) $sides);
        }

        if ($peripheries) {
            $attributes = ($attributes)('peripheries', (string) $peripheries);
        }

        if ($distortion) {
            $attributes = ($attributes)(
                'distortion',
                \sprintf('%0.1f', $distortion),
            );
        }

        if ($skew) {
            $attributes = ($attributes)(
                'skew',
                \sprintf('%0.1f', $skew),
            );
        }

        return new self($attributes);
    }

    public static function ellipse(float $width = .75, float $height = .5): self
    {
        return new self(Map::of(
            ['shape', 'ellipse'],
            ['width', (string) $width],
            ['height', (string) $height],
        ));
    }

    public static function circle(): self
    {
        return new self(Map::of(['shape', 'circle']));
    }

    public static function point(): self
    {
        return new self(Map::of(['shape', 'point']));
    }

    public static function egg(): self
    {
        return new self(Map::of(['shape', 'egg']));
    }

    public static function triangle(): self
    {
        return new self(Map::of(['shape', 'triangle']));
    }

    public static function plaintext(): self
    {
        return new self(Map::of(['shape', 'plaintext']));
    }

    public static function diamond(): self
    {
        return new self(Map::of(['shape', 'diamond']));
    }

    public static function trapezium(): self
    {
        return new self(Map::of(['shape', 'trapezium']));
    }

    public static function parallelogram(): self
    {
        return new self(Map::of(['shape', 'parallelogram']));
    }

    public static function house(): self
    {
        return new self(Map::of(['shape', 'house']));
    }

    public static function hexagon(): self
    {
        return new self(Map::of(['shape', 'hexagon']));
    }

    public static function octagon(): self
    {
        return new self(Map::of(['shape', 'octagon']));
    }

    public static function doublecircle(): self
    {
        return new self(Map::of(['shape', 'doublecircle']));
    }

    public static function doubleoctagon(): self
    {
        return new self(Map::of(['shape', 'doubleoctagon']));
    }

    public static function tripleoctagon(): self
    {
        return new self(Map::of(['shape', 'tripleoctagon']));
    }

    public static function invtriangle(): self
    {
        return new self(Map::of(['shape', 'invtriangle']));
    }

    public static function invtrapezium(): self
    {
        return new self(Map::of(['shape', 'invtrapezium']));
    }

    public static function invhouse(): self
    {
        return new self(Map::of(['shape', 'invhouse']));
    }

    public static function Mdiamond(): self
    {
        return new self(Map::of(['shape', 'Mdiamond']));
    }

    public static function Msquare(): self
    {
        return new self(Map::of(['shape', 'Msquare']));
    }

    public static function Mcircle(): self
    {
        return new self(Map::of(['shape', 'Mcircle']));
    }

    public static function none(): self
    {
        return new self(Map::of(['shape', 'none']));
    }

    public static function record(): self
    {
        return new self(Map::of(['shape', 'record']));
    }

    public static function Mrecord(): self
    {
        return new self(Map::of(['shape', 'Mrecord']));
    }

    public function withColor(RGBA $color): self
    {
        return new self(
            ($this->attributes)('color', $color->toString()),
        );
    }

    public function fillWithColor(RGBA $color): self
    {
        return new self(
            ($this->attributes)
                ('style', 'filled')
                ('fillcolor', $color->toString()),
        );
    }

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }
}
