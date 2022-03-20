<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz;

use Innmind\Graphviz\{
    Node,
    Node\Name,
    Node\Shape,
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
            (new Node($name))->name(),
        );
    }

    public function testEdges()
    {
        $node = new Node(new Name('foo'));
        $to = new Node(new Name('bar'));

        $this->assertInstanceOf(Set::class, $node->edges());
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
        $this->assertCount(0, $node->attributes());
    }

    public function testTarget()
    {
        $node = new Node(new Name('foo'));

        $this->assertNull($node->target($url = Url::of('example.com')));
        $this->assertCount(1, $node->attributes());
        $this->assertSame($url->toString(), $node->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testLabel()
    {
        $node = new Node(new Name('foo'));

        $this->assertNull($node->displayAs('watev'));
        $this->assertCount(1, $node->attributes());
        $this->assertSame('watev', $node->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShape()
    {
        $node = new Node(new Name('foo'));

        $this->assertNull($node->shaped($shape = Shape::ellipse()));
        $this->assertFalse($node->attributes()->empty());
        $this->assertSame('ellipse', $node->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
