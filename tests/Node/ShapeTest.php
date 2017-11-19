<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\Node\Shape;
use Innmind\Colour\Colour;
use PHPUnit\Framework\TestCase;

class ShapeTest extends TestCase
{
    public function testBox()
    {
        $this->assertInstanceOf(Shape::class, Shape::box());
        $this->assertSame('shape="box"', (string) Shape::box());
    }

    public function testPolygon()
    {
        $this->assertInstanceOf(Shape::class, Shape::polygon());
        $this->assertSame('shape="polygon"', (string) Shape::polygon());
        $this->assertSame(
            'shape="polygon", sides="5", peripheries="3", distortion="0.7", skew="-0.1"',
            (string) Shape::polygon(5, 3, 0.74, -0.15)
        );
    }

    public function testEllipse()
    {
        $this->assertInstanceOf(Shape::class, Shape::ellipse());
        $this->assertSame('shape="ellipse", width="0.75", height="0.5"', (string) Shape::ellipse());
        $this->assertSame(
            'shape="ellipse", width="0.2", height="2"',
            (string) Shape::ellipse(.2, 2)
        );
    }

    public function testCircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::circle());
        $this->assertSame('shape="circle"', (string) Shape::circle());
    }

    public function testPoint()
    {
        $this->assertInstanceOf(Shape::class, Shape::point());
        $this->assertSame('shape="point"', (string) Shape::point());
    }

    public function testEgg()
    {
        $this->assertInstanceOf(Shape::class, Shape::egg());
        $this->assertSame('shape="egg"', (string) Shape::egg());
    }

    public function testTriangle()
    {
        $this->assertInstanceOf(Shape::class, Shape::triangle());
        $this->assertSame('shape="triangle"', (string) Shape::triangle());
    }

    public function testPlaintext()
    {
        $this->assertInstanceOf(Shape::class, Shape::plaintext());
        $this->assertSame('shape="plaintext"', (string) Shape::plaintext());
    }

    public function testDiamond()
    {
        $this->assertInstanceOf(Shape::class, Shape::diamond());
        $this->assertSame('shape="diamond"', (string) Shape::diamond());
    }

    public function testTrapezium()
    {
        $this->assertInstanceOf(Shape::class, Shape::trapezium());
        $this->assertSame('shape="trapezium"', (string) Shape::trapezium());
    }

    public function testParallelogram()
    {
        $this->assertInstanceOf(Shape::class, Shape::parallelogram());
        $this->assertSame('shape="parallelogram"', (string) Shape::parallelogram());
    }

    public function testHouse()
    {
        $this->assertInstanceOf(Shape::class, Shape::house());
        $this->assertSame('shape="house"', (string) Shape::house());
    }

    public function testHexagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::hexagon());
        $this->assertSame('shape="hexagon"', (string) Shape::hexagon());
    }

    public function testOctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::octagon());
        $this->assertSame('shape="octagon"', (string) Shape::octagon());
    }

    public function testDoublecircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::doublecircle());
        $this->assertSame('shape="doublecircle"', (string) Shape::doublecircle());
    }

    public function testDoubleoctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::doubleoctagon());
        $this->assertSame('shape="doubleoctagon"', (string) Shape::doubleoctagon());
    }

    public function testTripleoctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::tripleoctagon());
        $this->assertSame('shape="tripleoctagon"', (string) Shape::tripleoctagon());
    }

    public function testInvtriangle()
    {
        $this->assertInstanceOf(Shape::class, Shape::invtriangle());
        $this->assertSame('shape="invtriangle"', (string) Shape::invtriangle());
    }

    public function testInvtrapezium()
    {
        $this->assertInstanceOf(Shape::class, Shape::invtrapezium());
        $this->assertSame('shape="invtrapezium"', (string) Shape::invtrapezium());
    }

    public function testInvhouse()
    {
        $this->assertInstanceOf(Shape::class, Shape::invhouse());
        $this->assertSame('shape="invhouse"', (string) Shape::invhouse());
    }

    public function testMdiamond()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mdiamond());
        $this->assertSame('shape="Mdiamond"', (string) Shape::Mdiamond());
    }

    public function testMsquare()
    {
        $this->assertInstanceOf(Shape::class, Shape::Msquare());
        $this->assertSame('shape="Msquare"', (string) Shape::Msquare());
    }

    public function testMcircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mcircle());
        $this->assertSame('shape="Mcircle"', (string) Shape::Mcircle());
    }

    public function testNone()
    {
        $this->assertInstanceOf(Shape::class, Shape::none());
        $this->assertSame('shape="none"', (string) Shape::none());
    }

    public function testRecord()
    {
        $this->assertInstanceOf(Shape::class, Shape::record());
        $this->assertSame('shape="record"', (string) Shape::record());
    }

    public function testMrecord()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mrecord());
        $this->assertSame('shape="Mrecord"', (string) Shape::Mrecord());
    }

    public function testWithColor()
    {
        $shape = Shape::box();
        $shape2 = $shape->withColor(Colour::fromString('blue'));

        $this->assertInstanceOf(Shape::class, $shape2);
        $this->assertNotSame($shape2, $shape);
        $this->assertSame('shape="box"', (string) $shape);
        $this->assertSame('shape="box", color="#0000ff"', (string) $shape2);
    }

    public function testFillWithColor()
    {
        $shape = Shape::box();
        $shape2 = $shape->fillWithColor(Colour::fromString('blue'));

        $this->assertInstanceOf(Shape::class, $shape2);
        $this->assertNotSame($shape2, $shape);
        $this->assertSame('shape="box"', (string) $shape);
        $this->assertSame('shape="box", style="filled", fillcolor="#0000ff"', (string) $shape2);
    }
}
