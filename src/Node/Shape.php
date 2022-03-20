<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Node;

use Innmind\Colour\RGBA;
use Innmind\Immutable\Map;

final class Shape
{
    /** @var Map<string, string> */
    private Map $attributes;

    private function __construct(string $name)
    {
        $this->attributes = Map::of(['shape', $name]);
    }

    public static function box(): self
    {
        return new self('box');
    }

    public static function polygon(
        int $sides = null,
        int $peripheries = null,
        float $distortion = null,
        float $skew = null,
    ): self {
        $self = new self('polygon');

        if ($sides) {
            $self->attributes = ($self->attributes)('sides', (string) $sides);
        }

        if ($peripheries) {
            $self->attributes = ($self->attributes)('peripheries', (string) $peripheries);
        }

        if ($distortion) {
            $self->attributes = ($self->attributes)(
                'distortion',
                \sprintf('%0.1f', $distortion),
            );
        }

        if ($skew) {
            $self->attributes = ($self->attributes)(
                'skew',
                \sprintf('%0.1f', $skew),
            );
        }

        return $self;
    }

    public static function ellipse(float $width = .75, float $height = .5): self
    {
        $self = new self('ellipse');
        $self->attributes = ($self->attributes)
            ('width', (string) $width)
            ('height', (string) $height);

        return $self;
    }

    public static function circle(): self
    {
        return new self('circle');
    }

    public static function point(): self
    {
        return new self('point');
    }

    public static function egg(): self
    {
        return new self('egg');
    }

    public static function triangle(): self
    {
        return new self('triangle');
    }

    public static function plaintext(): self
    {
        return new self('plaintext');
    }

    public static function diamond(): self
    {
        return new self('diamond');
    }

    public static function trapezium(): self
    {
        return new self('trapezium');
    }

    public static function parallelogram(): self
    {
        return new self('parallelogram');
    }

    public static function house(): self
    {
        return new self('house');
    }

    public static function hexagon(): self
    {
        return new self('hexagon');
    }

    public static function octagon(): self
    {
        return new self('octagon');
    }

    public static function doublecircle(): self
    {
        return new self('doublecircle');
    }

    public static function doubleoctagon(): self
    {
        return new self('doubleoctagon');
    }

    public static function tripleoctagon(): self
    {
        return new self('tripleoctagon');
    }

    public static function invtriangle(): self
    {
        return new self('invtriangle');
    }

    public static function invtrapezium(): self
    {
        return new self('invtrapezium');
    }

    public static function invhouse(): self
    {
        return new self('invhouse');
    }

    public static function Mdiamond(): self
    {
        return new self('Mdiamond');
    }

    public static function Msquare(): self
    {
        return new self('Msquare');
    }

    public static function Mcircle(): self
    {
        return new self('Mcircle');
    }

    public static function none(): self
    {
        return new self('none');
    }

    public static function record(): self
    {
        return new self('record');
    }

    public static function Mrecord(): self
    {
        return new self('Mrecord');
    }

    public function withColor(RGBA $color): self
    {
        $self = clone $this;
        $self->attributes = ($self->attributes)('color', $color->toString());

        return $self;
    }

    public function fillWithColor(RGBA $color): self
    {
        $self = clone $this;
        $self->attributes = ($self->attributes)
            ('style', 'filled')
            ('fillcolor', $color->toString());

        return $self;
    }

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map
    {
        return $this->attributes;
    }
}
