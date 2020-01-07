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
    Set,
    Map,
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

    public function testNamed()
    {
        $node = Node::named('foo');

        $this->assertInstanceOf(Node::class, $node);
        $this->assertInstanceOf(Name::class, $node->name());
        $this->assertSame('foo', $node->name()->toString());
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

        $this->assertInstanceOf(Set::class, $node->edges());
        $this->assertSame(Edge::class, $node->edges()->type());
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

        $this->assertInstanceOf(Map::class, $node->attributes());
        $this->assertSame('string', $node->attributes()->keyType());
        $this->assertSame('string', $node->attributes()->valueType());
        $this->assertCount(0, $node->attributes());
    }

    public function testTarget()
    {
        $node = new Node(new Name('foo'));

        $this->assertNull($node->target($url = Url::of('example.com')));
        $this->assertCount(1, $node->attributes());
        $this->assertSame($url->toString(), $node->attributes()->get('URL'));
    }

    public function testLabel()
    {
        $node = new Node(new Name('foo'));

        $this->assertNull($node->displayAs('watev'));
        $this->assertCount(1, $node->attributes());
        $this->assertSame('watev', $node->attributes()->get('label'));
    }

    public function testShape()
    {
        $node = new Node(new Name('foo'));

        $this->assertNull($node->shaped($shape = Shape::ellipse()));
        $this->assertTrue($node->hasAttributes());
        $this->assertSame('ellipse', $node->attributes()->get('shape'));
    }
}
