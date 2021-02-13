<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\Node\Shape;
use Innmind\Colour\Colour;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ShapeTest extends TestCase
{
    public function testBox()
    {
        $this->assertInstanceOf(Shape::class, Shape::box());
        $this->assertInstanceOf(Map::class, Shape::box()->attributes());
        $this->assertSame('string', Shape::box()->attributes()->keyType());
        $this->assertSame('string', Shape::box()->attributes()->valueType());
        $this->assertCount(1, Shape::box()->attributes());
        $this->assertSame('box', Shape::box()->attributes()->get('shape'));
    }

    public function testPolygon()
    {
        $this->assertInstanceOf(Shape::class, Shape::polygon());

        $shape = Shape::polygon();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('polygon', $shape->attributes()->get('shape'));

        $shape = Shape::polygon(5, 3, 0.74, -0.15);
        $this->assertCount(5, $shape->attributes());
        $this->assertSame('polygon', $shape->attributes()->get('shape'));
        $this->assertSame('5', $shape->attributes()->get('sides'));
        $this->assertSame('3', $shape->attributes()->get('peripheries'));
        $this->assertSame('0.7', $shape->attributes()->get('distortion'));
        $this->assertSame('-0.1', $shape->attributes()->get('skew'));
    }

    public function testEllipse()
    {
        $this->assertInstanceOf(Shape::class, Shape::ellipse());

        $shape = Shape::ellipse();
        $this->assertCount(3, $shape->attributes());
        $this->assertSame('ellipse', $shape->attributes()->get('shape'));
        $this->assertSame('0.75', $shape->attributes()->get('width'));
        $this->assertSame('0.5', $shape->attributes()->get('height'));

        $shape = Shape::ellipse(.2, 2);
        $this->assertCount(3, $shape->attributes());
        $this->assertSame('ellipse', $shape->attributes()->get('shape'));
        $this->assertSame('0.2', $shape->attributes()->get('width'));
        $this->assertSame('2', $shape->attributes()->get('height'));
    }

    public function testCircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::circle());
        $shape = Shape::circle();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('circle', $shape->attributes()->get('shape'));
    }

    public function testPoint()
    {
        $this->assertInstanceOf(Shape::class, Shape::point());
        $shape = Shape::point();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('point', $shape->attributes()->get('shape'));
    }

    public function testEgg()
    {
        $this->assertInstanceOf(Shape::class, Shape::egg());
        $shape = Shape::egg();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('egg', $shape->attributes()->get('shape'));
    }

    public function testTriangle()
    {
        $this->assertInstanceOf(Shape::class, Shape::triangle());
        $shape = Shape::triangle();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('triangle', $shape->attributes()->get('shape'));
    }

    public function testPlaintext()
    {
        $this->assertInstanceOf(Shape::class, Shape::plaintext());
        $shape = Shape::plaintext();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('plaintext', $shape->attributes()->get('shape'));
    }

    public function testDiamond()
    {
        $this->assertInstanceOf(Shape::class, Shape::diamond());
        $shape = Shape::diamond();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('diamond', $shape->attributes()->get('shape'));
    }

    public function testTrapezium()
    {
        $this->assertInstanceOf(Shape::class, Shape::trapezium());
        $shape = Shape::trapezium();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('trapezium', $shape->attributes()->get('shape'));
    }

    public function testParallelogram()
    {
        $this->assertInstanceOf(Shape::class, Shape::parallelogram());
        $shape = Shape::parallelogram();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('parallelogram', $shape->attributes()->get('shape'));
    }

    public function testHouse()
    {
        $this->assertInstanceOf(Shape::class, Shape::house());
        $shape = Shape::house();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('house', $shape->attributes()->get('shape'));
    }

    public function testHexagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::hexagon());
        $shape = Shape::hexagon();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('hexagon', $shape->attributes()->get('shape'));
    }

    public function testOctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::octagon());
        $shape = Shape::octagon();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('octagon', $shape->attributes()->get('shape'));
    }

    public function testDoublecircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::doublecircle());
        $shape = Shape::doublecircle();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('doublecircle', $shape->attributes()->get('shape'));
    }

    public function testDoubleoctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::doubleoctagon());
        $shape = Shape::doubleoctagon();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('doubleoctagon', $shape->attributes()->get('shape'));
    }

    public function testTripleoctagon()
    {
        $this->assertInstanceOf(Shape::class, Shape::tripleoctagon());
        $shape = Shape::tripleoctagon();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('tripleoctagon', $shape->attributes()->get('shape'));
    }

    public function testInvtriangle()
    {
        $this->assertInstanceOf(Shape::class, Shape::invtriangle());
        $shape = Shape::invtriangle();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('invtriangle', $shape->attributes()->get('shape'));
    }

    public function testInvtrapezium()
    {
        $this->assertInstanceOf(Shape::class, Shape::invtrapezium());
        $shape = Shape::invtrapezium();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('invtrapezium', $shape->attributes()->get('shape'));
    }

    public function testInvhouse()
    {
        $this->assertInstanceOf(Shape::class, Shape::invhouse());
        $shape = Shape::invhouse();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('invhouse', $shape->attributes()->get('shape'));
    }

    public function testMdiamond()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mdiamond());
        $shape = Shape::Mdiamond();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('Mdiamond', $shape->attributes()->get('shape'));
    }

    public function testMsquare()
    {
        $this->assertInstanceOf(Shape::class, Shape::Msquare());
        $shape = Shape::Msquare();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('Msquare', $shape->attributes()->get('shape'));
    }

    public function testMcircle()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mcircle());
        $shape = Shape::Mcircle();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('Mcircle', $shape->attributes()->get('shape'));
    }

    public function testNone()
    {
        $this->assertInstanceOf(Shape::class, Shape::none());
        $shape = Shape::none();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('none', $shape->attributes()->get('shape'));
    }

    public function testRecord()
    {
        $this->assertInstanceOf(Shape::class, Shape::record());
        $shape = Shape::record();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('record', $shape->attributes()->get('shape'));
    }

    public function testMrecord()
    {
        $this->assertInstanceOf(Shape::class, Shape::Mrecord());
        $shape = Shape::Mrecord();
        $this->assertCount(1, $shape->attributes());
        $this->assertSame('Mrecord', $shape->attributes()->get('shape'));
    }

    public function testWithColor()
    {
        $shape = Shape::box();
        $shape2 = $shape->withColor(Colour::of('blue'));

        $this->assertInstanceOf(Shape::class, $shape2);
        $this->assertNotSame($shape2, $shape);
        $this->assertCount(1, $shape->attributes());
        $this->assertCount(2, $shape2->attributes());
        $this->assertSame('#0000ff', $shape2->attributes()->get('color'));
    }

    public function testFillWithColor()
    {
        $shape = Shape::box();
        $shape2 = $shape->fillWithColor(Colour::of('blue'));

        $this->assertInstanceOf(Shape::class, $shape2);
        $this->assertNotSame($shape2, $shape);
        $this->assertCount(1, $shape->attributes());
        $this->assertCount(3, $shape2->attributes());
        $this->assertSame('#0000ff', $shape2->attributes()->get('fillcolor'));
        $this->assertSame('filled', $shape2->attributes()->get('style'));
    }
}
