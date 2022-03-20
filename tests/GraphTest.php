<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz;

use Innmind\Graphviz\{
    Graph,
    Graph\Name,
    Node,
    Exception\MixedGraphsNotAllowed
};
use Innmind\Colour\Colour;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};
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
        $this->assertSame('foo', Graph::directed('foo')->name()->toString());
        $this->assertSame('foo', Graph::undirected('foo')->name()->toString());
    }

    public function testAdd()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(Set::class, $graph->roots());
        $this->assertCount(0, $graph->roots());
        $this->assertInstanceOf(Set::class, $graph->nodes());
        $this->assertCount(0, $graph->nodes());

        $root = Node::named('main');
        $root->linkedTo($second = Node::named('second'));
        $second->linkedTo($third = Node::named('third'));

        $this->assertNull($graph->add($root));
        $this->assertNull($graph->add($second));
        $this->assertNull($graph->add($third));
        $this->assertCount(1, $graph->roots());
        $this->assertSame($root, $graph->roots()->find(static fn() => true)->match(
            static fn($root) => $root,
            static fn() => null,
        ));
        $this->assertCount(3, $graph->nodes());
        $this->assertSame([$root, $second, $third], $graph->nodes()->toList());
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
        $this->assertCount(0, $root->clusters());

        $cluster = Graph::directed('foo');
        $this->assertNull($root->cluster($cluster));
        $this->assertCount(1, $root->clusters());
        $this->assertSame($cluster, $root->clusters()->find(static fn() => true)->match(
            static fn($cluster) => $cluster,
            static fn() => null,
        ));
    }

    public function testAttributes()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(Map::class, $graph->attributes());
        $this->assertCount(0, $graph->attributes());
    }

    public function testDisplayAs()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->displayAs('watev'));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('watev', $graph->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testFillWithColor()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->fillWithColor(Colour::red->toRGBA()));
        $this->assertCount(2, $graph->attributes());
        $this->assertSame('filled', $graph->attributes()->get('style')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
        $this->assertSame('#ff0000', $graph->attributes()->get('fillcolor')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testColorizeBorderWith()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->colorizeBorderWith(Colour::red->toRGBA()));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('#ff0000', $graph->attributes()->get('color')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTarget()
    {
        $graph = Graph::directed();

        $this->assertNull($graph->target(Url::of('example.com')));
        $this->assertCount(1, $graph->attributes());
        $this->assertSame('example.com', $graph->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
