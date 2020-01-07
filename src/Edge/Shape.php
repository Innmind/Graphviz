<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

final class Shape
{
    private string $value;
    private string $modifier = '';
    private string $side = '';

    private function __construct(string $value)
    {
        $this->value = $value;
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
        $self = clone $this;
        $self->modifier = 'o';

        return $self;
    }

    public function left(): self
    {
        $self = clone $this;
        $self->side = 'l';

        return $self;
    }

    public function right(): self
    {
        $self = clone $this;
        $self->side = 'r';

        return $self;
    }

    public function toString(): string
    {
        return $this->modifier.$this->side.$this->value;
    }
}
