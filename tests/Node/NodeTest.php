<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node\Node,
    Node\Name,
    Node as NodeInterface,
    Edge
};
use Innmind\Immutable\SetInterface;
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
}
