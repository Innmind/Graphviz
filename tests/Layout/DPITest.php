<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Layout\DPI,
    Exception\DomainException
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait
};

class DPITest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\pos())
            ->then(function(int $int): void {
                $this->assertSame($int, (new DPI($int))->toInt());
            });
    }

    public function testThrowWhenLowerThanOne()
    {
        $this
            ->forAll(Generator\neg())
            ->then(function(int $int): void {
                $this->expectException(DomainException::class);

                new DPI($int);
            });

        $this->expectException(DomainException::class);

        new DPI(0);
    }
}
