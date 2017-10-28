<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node\Node,
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
            new Node('foo')
        );
    }

    public function testName()
    {
        $this->assertSame('foo', (new Node('foo'))->name());
    }

    /**
     * @expectedException Innmind\Graphviz\Exception\DomainException
     */
    public function testThrowWhenEmptyName()
    {
        new Node('');
    }

    public function testEdges()
    {
        $node = new Node('foo');
        $to = new Node('bar');

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
