<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

/**
 * @psalm-immutable
 */
enum Shape
{
    case box;
    case crow;
    case curve;
    case icurve;
    case diamond;
    case dot;
    case inv;
    case none;
    case normal;
    case tee;
    case vee;

    /**
     * @psalm-pure
     */
    public static function box(): self
    {
        return self::box;
    }

    /**
     * @psalm-pure
     */
    public static function crow(): self
    {
        return self::crow;
    }

    /**
     * @psalm-pure
     */
    public static function curve(): self
    {
        return self::curve;
    }

    /**
     * @psalm-pure
     */
    public static function icurve(): self
    {
        return self::icurve;
    }

    /**
     * @psalm-pure
     */
    public static function diamond(): self
    {
        return self::diamond;
    }

    /**
     * @psalm-pure
     */
    public static function dot(): self
    {
        return self::dot;
    }

    /**
     * @psalm-pure
     */
    public static function inv(): self
    {
        return self::inv;
    }

    /**
     * @psalm-pure
     */
    public static function none(): self
    {
        return self::none;
    }

    /**
     * @psalm-pure
     */
    public static function normal(): self
    {
        return self::normal;
    }

    /**
     * @psalm-pure
     */
    public static function tee(): self
    {
        return self::tee;
    }

    /**
     * @psalm-pure
     */
    public static function vee(): self
    {
        return self::vee;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
