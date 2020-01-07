<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Graph;

use Innmind\Graphviz\{
    Graph\Graph,
    Graph\Name,
    Graph as GraphInterface,
    Node,
    Exception\MixedGraphsNotAllowed
};
use Innmind\Colour\Colour;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};
use function Innmind\Immutable\{
    first,
    unwrap,
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
        $this->assertSame('foo', Graph::directed('foo')->name()->toString());
        $this->assertSame('foo', Graph::undirected('foo')->name()->toString());
    }

    public function testAdd()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(Set::class, $graph->roots());
        $this->assertSame(Node::class, $graph->roots()->type());
        $this->assertCount(0, $graph->roots());
        $this->assertInstanceOf(Set::class, $graph->nodes());
        $this->assertSame(Node::class, $graph->nodes()->type());
        $this->assertCount(0, $graph->nodes());

        $root = Node\Node::named('main');
        $root->linkedTo($second = Node\Node::named('second'));
        $main = Node\Node::named('main');
        $second->linkedTo($third = Node\Node::named('third'));
        $third->linkedTo($main);


        $this->assertNull($graph->add($root));
        $this->assertCount(1, $graph->roots());
        $this->assertSame($root, first($graph->roots()));
        $this->assertCount(3, $graph->nodes());
        $this->assertSame([$main, $second, $third], unwrap($graph->nodes()));
    }

    public function testThrowWhenMixedGraphs()
    {
        $this->expectException(MixedGraphsNotAllowed::class);

        Graph::directed()->cluster(Graph::undirected());
    }

    public function testCluster()
    {
        $root = Graph::directed();

        $this->assertInstanceOf(Set::class, $root->clusters());
        $this->assertSame(GraphInterface::class, $root->clusters()->type());
        $this->assertCount(0, $root->clusters());

        $cluster = Graph::directed('foo');
        $this->assertNull($root->cluster($cluster));
        $this->assertCount(1, $root->clusters());
        $this->assertSame($cluster, first($root->clusters()));
    }

    public function testAttributes()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(Map::class, $graph->attributes());
        $this->assertSame('string', $graph->attributes()->keyType());
        $this->assertSame('string', $graph->attributes()->valueType());
        $this->assertCount(0, $graph->attributes());
    }

    public function testDisplayAs()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->displayAs('watev'));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('watev', $graph->attributes()->get('label'));
    }

    public function testFillWithColor()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->fillWithColor(Colour::of('red')));
        $this->assertCount(2, $graph->attributes());
        $this->assertSame('filled', $graph->attributes()->get('style'));
        $this->assertSame('#ff0000', $graph->attributes()->get('fillcolor'));
    }

    public function testColorizeBorderWith()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->colorizeBorderWith(Colour::of('red')));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('#ff0000', $graph->attributes()->get('color'));
    }

    public function testTarget()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->target(Url::of('example.com')));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('example.com', $graph->attributes()->get('URL'));
    }
}
