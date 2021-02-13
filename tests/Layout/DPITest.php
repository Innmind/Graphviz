<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Layout\DPI,
    Exception\DomainException
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class DPITest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set\Integers::above(1))
            ->then(function(int $int): void {
                $this->assertSame($int, (new DPI($int))->toInt());
            });
    }

    public function testThrowWhenLowerThanOne()
    {
        $this
            ->forAll(Set\Integers::below(0))
            ->then(function(int $int): void {
                try {
                    new DPI($int);
                } catch (\Throwable $e) {
                    $this->assertInstanceOf(DomainException::class, $e);
                }
            });
    }
}
