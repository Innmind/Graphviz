<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\Layout\Size;
use PHPUnit\Framework\TestCase;

class SizeTest extends TestCase
{
    public function testInterface()
    {
        $size = new Size(1, 2);

        $this->assertSame(1, $size->width());
        $this->assertSame(2, $size->height());
    }

    /**
     * @expectedException Innmind\Graphviz\Exception\DomainException
     */
    public function testThrowWhenWidthTooLow()
    {
        new Size(0, 1);
    }

    /**
     * @expectedException Innmind\Graphviz\Exception\DomainException
     */
    public function testThrowWhenHeightTooLow()
    {
        new Size(1, 0);
    }
}
