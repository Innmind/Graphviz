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
use Innmind\Immutable\MapInterface;
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
        $this->assertInstanceOf(MapInterface::class, $edge->attributes());
        $this->assertSame('string', (string) $edge->attributes()->keyType());
        $this->assertSame('string', (string) $edge->attributes()->valueType());
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

        $this->assertSame($edge, $edge->asBidirectional());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('both', $edge->attributes()->get('dir'));
    }

    public function testWithoutDirection()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertSame($edge, $edge->withoutDirection());
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('none', $edge->attributes()->get('dir'));
    }

    public function testShaped()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertSame(
            $edge,
            $edge->shaped(
                Shape::box(),
                Shape::vee(),
                Shape::tee(),
                Shape::dot()
            )
        );
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowhead'));
    }

    public function testShapedWhenBidirectional()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );
        $edge
            ->asBidirectional()
            ->shaped(
                Shape::box(),
                Shape::vee(),
                Shape::tee(),
                Shape::dot()
            );

        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(3, $edge->attributes());
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowtail'));
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowhead'));
    }

    public function testDisplayAs()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertSame($edge, $edge->displayAs('foo'));
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('foo', $edge->attributes()->get('label'));
    }

    public function testUseColor()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertSame($edge, $edge->useColor(Colour::fromString('red')));
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('#ff0000', $edge->attributes()->get('color'));
    }

    public function testTarget()
    {
        $edge = new Edge(
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        );

        $this->assertSame($edge, $edge->target(Url::fromString('example.com')));
        $this->assertTrue($edge->hasAttributes());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('example.com', $edge->attributes()->get('URL'));
    }
}
