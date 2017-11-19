<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node\Node,
    Node\Name,
    Node\Shape,
    Node as NodeInterface,
    Edge
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeInterface::class,
            new Node(new Name('foo'))
        );
    }

    public function testName()
    {
        $name = new Name('foo');
        $this->assertSame(
            $name,
            (new Node($name))->name()
        );
    }

    public function testEdges()
    {
        $node = new Node(new Name('foo'));
        $to = new Node(new Name('bar'));

        $this->assertInstanceOf(SetInterface::class, $node->edges());
        $this->assertSame(Edge::class, (string) $node->edges()->type());
        $this->assertCount(0, $node->edges());

        $edge = $node->linkedTo($to);

        $this->assertInstanceOf(Edge::class, $edge);
        $this->assertSame($node, $edge->from());
        $this->assertSame($to, $edge->to());
        $this->assertCount(1, $node->edges());
        $this->assertCount(0, $to->edges());
    }

    public function testAttributes()
    {
        $node = new Node(new Name('foo'));

        $this->assertInstanceOf(MapInterface::class, $node->attributes());
        $this->assertSame('string', (string) $node->attributes()->keyType());
        $this->assertSame('mixed', (string) $node->attributes()->valueType());
        $this->assertCount(0, $node->attributes());
    }

    public function testTarget()
    {
        $node = new Node(new Name('foo'));

        $this->assertSame($node, $node->target($url = Url::fromString('example.com')));
        $this->assertCount(1, $node->attributes());
        $this->assertSame($url, $node->attributes()->get('target'));
    }

    public function testLabel()
    {
        $node = new Node(new Name('foo'));

        $this->assertSame($node, $node->displayAs('watev'));
        $this->assertCount(1, $node->attributes());
        $this->assertSame('watev', $node->attributes()->get('label'));
    }

    public function testShape()
    {
        $node = new Node(new Name('foo'));

        $this->assertSame('shape="box"', (string) $node->shape());
        $this->assertFalse($node->hasCustomShape());
        $this->assertSame($node, $node->shaped($shape = Shape::ellipse()));
        $this->assertTrue($node->hasCustomShape());
        $this->assertSame($shape, $node->shape());
    }
}
