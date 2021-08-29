<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Edge;

use Innmind\Graphviz\{
    Edge\Edge,
    Edge\Shape,
    Edge as EdgeInterface,
    Node
};
use Innmind\Colour\Colour;
use Innmind\Url\Url;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class EdgeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            EdgeInterface::class,
            $edge = new Edge(
                $this->createMock(Node::class),
                $this->createMock(Node::class)
            )
        );
        $this->assertFalse($edge->hasAttributes());
        $this->assertInstanceOf(Map::class, $edge->attributes());
    }

    public function testNodes()
    {
        $edge = new Edge(
            $from = $this->createMock(Node::class),
            $to = $this->createMock(Node::class)
        );

        $this->assertSame($from, $edge->from());
        $this->assertSame($to, $edge->to());
    }

    public function testAsBidirectional()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->asBidirectional());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('both', $edge->attributes()->get('dir')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testWithoutDirection()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->withoutDirection());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('none', $edge->attributes()->get('dir')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShaped()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull(
            $edge->shaped(
                Shape::box(),
                Shape::vee(),
                Shape::tee(),
                Shape::dot()
            ),
        );
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowhead')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShapedWhenBidirectional()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );
        $edge->asBidirectional();
        $edge->shaped(
            Shape::box(),
            Shape::vee(),
            Shape::tee(),
            Shape::dot()
        );

        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(3, $edge->attributes());
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowtail')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowhead')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testDisplayAs()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->displayAs('foo'));
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('foo', $edge->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testUseColor()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->useColor(Colour::of('red')));
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('#ff0000', $edge->attributes()->get('color')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTarget()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->target(Url::of('example.com')));
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('example.com', $edge->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testDotted()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->dotted());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('dotted', $edge->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testBold()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->bold());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('bold', $edge->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testFilled()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertNull($edge->filled());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('filled', $edge->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
