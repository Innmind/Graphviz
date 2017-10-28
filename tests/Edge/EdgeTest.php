<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Edge;

use Innmind\Graphviz\{
    Edge\Edge,
    Edge as EdgeInterface,
    Node
};
use PHPUnit\Framework\TestCase;

class EdgeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            EdgeInterface::class,
            new Edge(
                $this->createMock(Node::class),
                $this->createMock(Node::class)
            )
        );
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
}
