<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Graph;

final class Rankdir
{
    private static $lr;
    private static $tb;
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function topToBottom(): self
    {
        return self::$tb ?? self::$tb = new self('TB');
    }

    public static function leftToRight(): self
    {
        return self::$lr ?? self::$lr = new self('LR');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
