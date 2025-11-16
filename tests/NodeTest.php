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
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

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
        $to = Name::of('bar');

        $this->assertInstanceOf(Set::class, $node->edges());
        $this->assertSame(0, $node->edges()->size());

        $node = $node->linkedTo(
            $to,
            function($edge) use ($node, $to) {
                $this->assertInstanceOf(Edge::class, $edge);
                $this->assertSame($node->name(), $edge->from());
                $this->assertSame($to, $edge->to());

                return $edge;
            },
        );

        $this->assertSame(1, $node->edges()->size());
    }

    public function testAttributes()
    {
        $node = Node::of(Name::of('foo'));

        $this->assertInstanceOf(Map::class, $node->attributes());
        $this->assertSame(0, $node->attributes()->size());
    }

    public function testTarget()
    {
        $node = Node::of(Name::of('foo'))->target($url = Url::of('example.com'));

        $this->assertSame(1, $node->attributes()->size());
        $this->assertSame($url->toString(), $node->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testLabel()
    {
        $node = Node::of(Name::of('foo'))->displayAs('watev');

        $this->assertSame(1, $node->attributes()->size());
        $this->assertSame('watev', $node->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShape()
    {
        $node = Node::of(Name::of('foo'))->shaped(Shape::ellipse());

        $this->assertFalse($node->attributes()->empty());
        $this->assertSame('ellipse', $node->attributes()->get('shape')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
