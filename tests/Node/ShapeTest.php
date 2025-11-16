<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\Node\Shape;
use Innmind\Colour\Colour;
use Innmind\Immutable\Map;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ShapeTest extends TestCase
{
    public function testBox()
    {
        $this->assertInstanceOf(Shape::class, Shape::box());
        $this->assertInstanceOf(Map::class, Shape::box()->attributes());
        $this->assertSame(1, Shape::box()->attributes()->size());
        $this->assertSame('box', Shape::box()->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testPolygon()
    {
        $this->assertInstanceOf(Shape::class, Shape::polygon());

        $shape = Shape::polygon();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('polygon', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));

        $shape = Shape::polygon(5, 3, 0.74, -0.15);
        $this->assertSame(5, $shape->attributes()->size());
        $this->assertSame('polygon', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('5', $shape->attributes()->get('sides')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('3', $shape->attributes()->get('peripheries')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('0.7', $shape->attributes()->get('distortion')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('-0.1', $shape->attributes()->get('skew')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testEllipse()
    {
        $this->assertInstanceOf(Shape::class, Shape::ellipse());

        $shape = Shape::ellipse();
        $this->assertSame(3, $shape->attributes()->size());
        $this->assertSame('ellipse', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('0.75', $shape->attributes()->get('width')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('0.5', $shape->attributes()->get('height')->match(
            static fn($value) => $value,
            static fn() => null,
        ));

        $shape = Shape::ellipse(.2, 2);
        $this->assertSame(3, $shape->attributes()->size());
        $this->assertSame('ellipse', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('0.2', $shape->attributes()->get('width')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('2', $shape->attributes()->get('height')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testCircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::circle());
        $shape = Shape::circle();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('circle', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testPoint()
    {
        $this->assertInstanceOf(Shape::class, Shape::point());
        $shape = Shape::point();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('point', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testEgg()
    {
        $this->assertInstanceOf(Shape::class, Shape::egg());
        $shape = Shape::egg();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('egg', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTriangle()
    {
        $this->assertInstanceOf(Shape::class, Shape::triangle());
        $shape = Shape::triangle();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('triangle', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testPlaintext()
    {
        $this->assertInstanceOf(Shape::class, Shape::plaintext());
        $shape = Shape::plaintext();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('plaintext', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testDiamond()
    {
        $this->assertInstanceOf(Shape::class, Shape::diamond());
        $shape = Shape::diamond();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('diamond', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTrapezium()
    {
        $this->assertInstanceOf(Shape::class, Shape::trapezium());
        $shape = Shape::trapezium();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('trapezium', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testParallelogram()
    {
        $this->assertInstanceOf(Shape::class, Shape::parallelogram());
        $shape = Shape::parallelogram();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('parallelogram', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testHouse()
    {
        $this->assertInstanceOf(Shape::class, Shape::house());
        $shape = Shape::house();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('house', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testHexagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::hexagon());
        $shape = Shape::hexagon();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('hexagon', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testOctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::octagon());
        $shape = Shape::octagon();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('octagon', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testDoublecircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::doublecircle());
        $shape = Shape::doublecircle();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('doublecircle', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testDoubleoctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::doubleoctagon());
        $shape = Shape::doubleoctagon();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('doubleoctagon', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTripleoctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::tripleoctagon());
        $shape = Shape::tripleoctagon();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('tripleoctagon', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testInvtriangle()
    {
        $this->assertInstanceOf(Shape::class, Shape::invtriangle());
        $shape = Shape::invtriangle();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('invtriangle', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testInvtrapezium()
    {
        $this->assertInstanceOf(Shape::class, Shape::invtrapezium());
        $shape = Shape::invtrapezium();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('invtrapezium', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testInvhouse()
    {
        $this->assertInstanceOf(Shape::class, Shape::invhouse());
        $shape = Shape::invhouse();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('invhouse', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testMdiamond()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mdiamond());
        $shape = Shape::Mdiamond();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('Mdiamond', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testMsquare()
    {
        $this->assertInstanceOf(Shape::class, Shape::Msquare());
        $shape = Shape::Msquare();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('Msquare', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testMcircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mcircle());
        $shape = Shape::Mcircle();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('Mcircle', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testNone()
    {
        $this->assertInstanceOf(Shape::class, Shape::none());
        $shape = Shape::none();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('none', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testRecord()
    {
        $this->assertInstanceOf(Shape::class, Shape::record());
        $shape = Shape::record();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('record', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testMrecord()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mrecord());
        $shape = Shape::Mrecord();
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame('Mrecord', $shape->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testWithColor()
    {
        $shape = Shape::box();
        $shape2 = $shape->withColor(Colour::blue->toRGBA());

        $this->assertInstanceOf(Shape::class, $shape2);
        $this->assertNotSame($shape2, $shape);
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame(2, $shape2->attributes()->size());
        $this->assertSame('#0000ff', $shape2->attributes()->get('color')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testFillWithColor()
    {
        $shape = Shape::box();
        $shape2 = $shape->fillWithColor(Colour::blue->toRGBA());

        $this->assertInstanceOf(Shape::class, $shape2);
        $this->assertNotSame($shape2, $shape);
        $this->assertSame(1, $shape->attributes()->size());
        $this->assertSame(3, $shape2->attributes()->size());
        $this->assertSame('#0000ff', $shape2->attributes()->get('fillcolor')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('filled', $shape2->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
