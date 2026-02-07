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
    #[\NoDiscard]
    public static function box(): self
    {
        return self::box;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function crow(): self
    {
        return self::crow;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function curve(): self
    {
        return self::curve;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function icurve(): self
    {
        return self::icurve;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function diamond(): self
    {
        return self::diamond;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function dot(): self
    {
        return self::dot;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function inv(): self
    {
        return self::inv;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return self::none;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function normal(): self
    {
        return self::normal;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function tee(): self
    {
        return self::tee;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function vee(): self
    {
        return self::vee;
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->name;
    }
}
