<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz;

use Innmind\Graphviz\{
    Graph,
    Graph\Name,
    Node
};
use Innmind\Immutable\SetInterface;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{
    public function testDirection()
    {
        $this->assertInstanceOf(Graph::class, Graph::directed());
        $this->assertInstanceOf(Graph::class, Graph::undirected());
        $this->assertTrue(Graph::directed()->isDirected());
        $this->assertFalse(Graph::undirected()->isDirected());
    }

    public function testName()
    {
        $this->assertInstanceOf(Name::class, Graph::directed('foo')->name());
        $this->assertInstanceOf(Name::class, Graph::undirected('foo')->name());
        $this->assertSame('foo', (string) Graph::directed('foo')->name());
        $this->assertSame('foo', (string) Graph::undirected('foo')->name());
    }

    public function testAdd()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(SetInterface::class, $graph->roots());
        $this->assertSame(Node::class, (string) $graph->roots()->type());
        $this->assertCount(0, $graph->roots());
        $this->assertInstanceOf(SetInterface::class, $graph->nodes());
        $this->assertSame(Node::class, (string) $graph->nodes()->type());
        $this->assertCount(0, $graph->nodes());

        $root = Node\Node::named('main');
        $root->linkedTo($second = Node\Node::named('second'));
        $main = Node\Node::named('main');
        $second->linkedTo($third = Node\Node::named('third'));
        $third->linkedTo($main);


        $this->assertSame($graph, $graph->add($root));
        $this->assertCount(1, $graph->roots());
        $this->assertSame($root, $graph->roots()->current());
        $this->assertCount(3, $graph->nodes());
        $this->assertSame([$main, $second, $third], $graph->nodes()->toPrimitive());
    }

    /**
     * @expectedException Innmind\Graphviz\Exception\MixedGraphsNotAllowed
     */
    public function testThrowWhenMixedGraphs()
    {
        Graph::directed()->cluster(Graph::undirected());
    }

    public function testCluster()
    {
        $root = Graph::directed();

        $this->assertInstanceOf(SetInterface::class, $root->clusters());
        $this->assertSame(Graph::class, (string) $root->clusters()->type());
        $this->assertCount(0, $root->clusters());

        $cluster = Graph::directed('foo');
        $this->assertSame($root, $root->cluster($cluster));
        $this->assertCount(1, $root->clusters());
        $this->assertSame($cluster, $root->clusters()->current());
    }
}
