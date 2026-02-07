<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz;

use Innmind\Graphviz\{
    Graph,
    Graph\Name,
    Node,
};
use Innmind\Colour\Colour;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

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
        $this->assertSame(0, $graph->roots()->size());
        $this->assertInstanceOf(Set::class, $graph->nodes());
        $this->assertSame(0, $graph->nodes()->size());

        $third = Node::named('third');
        $root = Node::named('main')->linkedTo(Node\Name::of('second'));
        $second = Node::named('second')->linkedTo($third->name());

        $graph = $graph
            ->add($root)
            ->add($second)
            ->add($third);

        $this->assertSame(1, $graph->roots()->size());
        $this->assertSame($root, $graph->roots()->find(static fn() => true)->match(
            static fn($root) => $root,
            static fn() => null,
        ));
        $this->assertSame(3, $graph->nodes()->size());
        $this->assertSame([$root, $second, $third], $graph->nodes()->toList());
    }

    public function testCluster()
    {
        $root = Graph::directed();

        $this->assertInstanceOf(Set::class, $root->clusters());
        $this->assertSame(0, $root->clusters()->size());

        $cluster = Graph::directed('foo');
        $root = $root->cluster($cluster);
        $this->assertSame(1, $root->clusters()->size());
        $this->assertSame($cluster, $root->clusters()->find(static fn() => true)->match(
            static fn($cluster) => $cluster,
            static fn() => null,
        ));
    }

    public function testAttributes()
    {
        $graph = Graph::directed();

        $this->assertInstanceOf(Map::class, $graph->attributes());
        $this->assertSame(0, $graph->attributes()->size());
    }

    public function testDisplayAs()
    {
        $graph = Graph::directed()->displayAs('watev');

        $this->assertSame(1, $graph->attributes()->size());
        $this->assertSame('watev', $graph->attributes()->get('label')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testFillWithColor()
    {
        $graph = Graph::directed()->fillWithColor(Colour::red->toRGBA());

        $this->assertSame(2, $graph->attributes()->size());
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
        $graph = Graph::directed()->colorizeBorderWith(Colour::red->toRGBA());

        $this->assertSame(1, $graph->attributes()->size());
        $this->assertSame('#ff0000', $graph->attributes()->get('color')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }

    public function testTarget()
    {
        $graph = Graph::directed()->target(Url::of('example.com'));

        $this->assertSame(1, $graph->attributes()->size());
        $this->assertSame('example.com', $graph->attributes()->get('URL')->match(
            static fn($value) => $value,
            static fn() => null,
        ));
    }
}
