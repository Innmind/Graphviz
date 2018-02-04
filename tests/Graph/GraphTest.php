<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Graph;

use Innmind\Graphviz\{
    Graph\Graph,
    Graph\Name,
    Graph as GraphInterface,
    Node
};
use Innmind\Colour\Colour;
use Innmind\Url\Url;
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(GraphInterface::class, Graph::directed());
    }

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
        $this->assertSame(GraphInterface::class, (string) $root->clusters()->type());
        $this->assertCount(0, $root->clusters());

        $cluster = Graph::directed('foo');
        $this->assertSame($root, $root->cluster($cluster));
        $this->assertCount(1, $root->clusters());
        $this->assertSame($cluster, $root->clusters()->current());
    }

    public function testAttributes()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(MapInterface::class, $graph->attributes());
        $this->assertSame('string', (string) $graph->attributes()->keyType());
        $this->assertSame('string', (string) $graph->attributes()->valueType());
        $this->assertCount(0, $graph->attributes());
    }

    public function testDisplayAs()
    {
        $graph = Graph::directed();

        $this->assertSame($graph, $graph->displayAs('watev'));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('watev', $graph->attributes()->get('label'));
    }

    public function testFillWithColor()
    {
        $graph = Graph::directed();

        $this->assertSame($graph, $graph->fillWithColor(Colour::fromString('red')));
        $this->assertCount(2, $graph->attributes());
        $this->assertSame('filled', $graph->attributes()->get('style'));
        $this->assertSame('#ff0000', $graph->attributes()->get('fillcolor'));
    }

    public function testColorizeBorderWith()
    {
        $graph = Graph::directed();

        $this->assertSame($graph, $graph->colorizeBorderWith(Colour::fromString('red')));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('#ff0000', $graph->attributes()->get('color'));
    }

    public function testTarget()
    {
        $graph = Graph::directed();

        $this->assertSame($graph, $graph->target(Url::fromString('example.com')));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('example.com', $graph->attributes()->get('URL'));
    }
}
