<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz;

use Innmind\Graphviz\{
    Edge,
    Edge\Shape,
    Node,
};
use Innmind\Colour\Colour;
use Innmind\Url\Url;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class EdgeTest extends TestCase
{
    public function testInterface()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );
        $this->assertTrue($edge->attributes()->empty());
        $this->assertInstanceOf(Map::class, $edge->attributes());
    }

    public function testNodes()
    {
        $edge = new Edge(
            $from = new Node(new Node\Name('a')),
            $to = new Node(new Node\Name('b')),
        );

        $this->assertSame($from, $edge->from());
        $this->assertSame($to, $edge->to());
    }

    public function testAsBidirectional()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->asBidirectional());
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('both', $edge->attributes()->get('dir')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testWithoutDirection()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->withoutDirection());
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('none', $edge->attributes()->get('dir')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShaped()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull(
            $edge->shaped(
                Shape::box(),
                Shape::vee(),
                Shape::tee(),
                Shape::dot(),
            ),
        );
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('boxveeteedot', $edge->attributes()->get('arrowhead')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testShapedWhenBidirectional()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );
        $edge->asBidirectional();
        $edge->shaped(
            Shape::box(),
            Shape::vee(),
            Shape::tee(),
            Shape::dot(),
        );

        $this->assertFalse($edge->attributes()->empty());
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
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->displayAs('foo'));
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('foo', $edge->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testUseColor()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->useColor(Colour::red->toRGBA()));
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('#ff0000', $edge->attributes()->get('color')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTarget()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->target(Url::of('example.com')));
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('example.com', $edge->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testDotted()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->dotted());
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('dotted', $edge->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testBold()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->bold());
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('bold', $edge->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testFilled()
    {
        $edge = new Edge(
            new Node(new Node\Name('a')),
            new Node(new Node\Name('b')),
        );

        $this->assertNull($edge->filled());
        $this->assertFalse($edge->attributes()->empty());
        $this->assertCount(1, $edge->attributes());
        $this->assertSame('filled', $edge->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
