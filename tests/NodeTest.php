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
        $name = Name::of('foo');
        $this->assertSame(
            $name,
            Node::of($name)->name(),
        );
    }

    public function testEdges()
    {
        $node = Node::of(Name::of('foo'));
        $to = Node::of(Name::of('bar'));

        $this->assertInstanceOf(Set::class, $node->edges());
        $this->assertCount(0, $node->edges());

        $this->assertNull($node->linkedTo(
            $to,
            function($edge) use ($node, $to) {
                $this->assertInstanceOf(Edge::class, $edge);
                $this->assertSame($node->name(), $edge->from());
                $this->assertSame($to, $edge->to());

                return $edge;
            },
        ));

        $this->assertCount(1, $node->edges());
        $this->assertCount(0, $to->edges());
    }

    public function testAttributes()
    {
        $node = Node::of(Name::of('foo'));

        $this->assertInstanceOf(Map::class, $node->attributes());
        $this->assertCount(0, $node->attributes());
    }

    public function testTarget()
    {
        $node = Node::of(Name::of('foo'));

        $this->assertNull($node->target($url = Url::of('example.com')));
        $this->assertCount(1, $node->attributes());
        $this->assertSame($url->toString(), $node->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testLabel()
    {
        $node = Node::of(Name::of('foo'));

        $this->assertNull($node->displayAs('watev'));
        $this->assertCount(1, $node->attributes());
        $this->assertSame('watev', $node->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShape()
    {
        $node = Node::of(Name::of('foo'));

        $this->assertNull($node->shaped($shape = Shape::ellipse()));
        $this->assertFalse($node->attributes()->empty());
        $this->assertSame('ellipse', $node->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
