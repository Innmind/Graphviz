<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

/**
 * @psalm-immutable
 */
final class Shape
{
    private string $value;
    private string $modifier;
    private string $side;

    private function __construct(
        string $value,
        string $modifier = '',
        string $side = '',
    ) {
        $this->value = $value;
        $this->modifier = $modifier;
        $this->side = $side;
    }

    public static function box(): self
    {
        return new self('box');
    }

    public static function crow(): self
    {
        return new self('crow');
    }

    public static function curve(): self
    {
        return new self('curve');
    }

    public static function icurve(): self
    {
        return new self('icurve');
    }

    public static function diamond(): self
    {
        return new self('diamond');
    }

    public static function dot(): self
    {
        return new self('dot');
    }

    public static function inv(): self
    {
        return new self('inv');
    }

    public static function none(): self
    {
        return new self('none');
    }

    public static function normal(): self
    {
        return new self('normal');
    }

    public static function tee(): self
    {
        return new self('tee');
    }

    public static function vee(): self
    {
        return new self('vee');
    }

    public function open(): self
    {
        return new self(
            $this->value,
            'o',
            $this->side,
        );
    }

    public function left(): self
    {
        return new self(
            $this->value,
            $this->modifier,
            'l',
        );
    }

    public function right(): self
    {
        return new self(
            $this->value,
            $this->modifier,
            'r',
        );
    }

    public function toString(): string
    {
        return $this->modifier.$this->side.$this->value;
    }
}
