<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\Layout\DPI;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    Set,
};

class DPITest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set::integers()->above(1))
            ->then(function(int $int): void {
                $this->assertSame($int, DPI::of($int)->toInt());
            });
    }
}
