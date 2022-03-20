<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Layout\Dot,
    Layout\DPI,
    Node,
    Node\Shape,
    Node\Name,
    Graph,
    Graph\Rankdir,
};
use Innmind\Filesystem\File\Content;
use Innmind\Url\Url;
use Innmind\Colour\Colour;
use PHPUnit\Framework\TestCase;

class DotTest extends TestCase
{
    /**
     * @see http://www.graphviz.org/pdf/dotguide.pdf
     */
    public function testFigure2()
    {
        $layout = Dot::of();
        $main = Node::of(Name::of('main'));
        $parse = Node::of(Name::of('parse'));
        $execute = Node::of(Name::of('execute'));
        $makeString = Node::of(Name::of('make_string'));
        $compare = Node::of(Name::of('compare'));
        $printf = Node::of(Name::of('printf'));
        $init = Node::of(Name::of('init'));
        $cleanup = Node::of(Name::of('cleanup'));

        $parse = $parse->linkedTo($execute);
        $main = $main
            ->linkedTo($parse)
            ->linkedTo($init)
            ->linkedTo($cleanup)
            ->linkedTo($printf);
        $execute = $execute
            ->linkedTo($makeString)
            ->linkedTo($printf)
            ->linkedTo($compare);
        $init = $init->linkedTo($makeString);

        $graph = Graph::directed()
            ->add($main)
            ->add($parse)
            ->add($execute)
            ->add($init);

        $output = $layout($graph);

        $expected = <<<DOT
digraph G {
    main -> parse;
    main -> init;
    main -> cleanup;
    main -> printf;
    parse -> execute;
    execute -> make_string;
    execute -> printf;
    execute -> compare;
    init -> make_string;
}
DOT;

        $this->assertInstanceOf(Content::class, $output);
        $this->assertSame($expected, $output->toString());
    }

    public function testDPI()
    {
        $dot = Dot::of(DPI::of(200));

        $graph = Graph::directed()->add(Node::named('main'));

        $output = $dot($graph);
        $expected = <<<DOT
digraph G {
    dpi="200";
    main;
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testNodeAttributes()
    {
        $dot = Dot::of();

        $main = Node::of(Name::of('main'));
        $parse = Node::of(Name::of('parse'));
        $execute = Node::of(Name::of('execute'));
        $makeString = Node::of(Name::of('make_string'));
        $compare = Node::of(Name::of('compare'));
        $printf = Node::of(Name::of('printf'));
        $init = Node::of(Name::of('init'));
        $cleanup = Node::of(Name::of('cleanup'));

        $parse = $parse
            ->linkedTo($execute)
            ->displayAs('Parse');
        $main = $main
            ->linkedTo($parse)
            ->linkedTo($init)
            ->linkedTo($cleanup)
            ->linkedTo($printf)
            ->shaped(Shape::circle())
            ->displayAs('Main Node')
            ->target(Url::of('example.com'));
        $execute = $execute
            ->linkedTo($makeString)
            ->linkedTo($printf)
            ->linkedTo($compare);
        $init = $init->linkedTo($makeString);

        $graph = Graph::directed()
            ->add($main)
            ->add($parse)
            ->add($execute)
            ->add($init);

        $output = $dot($graph);

        $expected = <<<DOT
digraph G {
    main -> parse;
    main -> init;
    main -> cleanup;
    main -> printf;
    parse -> execute;
    execute -> make_string;
    execute -> printf;
    execute -> compare;
    init -> make_string;
    main [shape="circle", label="Main Node", URL="example.com"];
    parse [label="Parse"];
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testEdgeAttributes()
    {
        $dot = Dot::of();

        $main = Node::of(Name::of('main'));
        $second = Node::of(Name::of('second'));
        $third = Node::of(Name::of('third'));
        $main = $main->linkedTo(
            $second,
            static fn($edge) => $edge
                ->displayAs('watev')
                ->withoutDirection(),
        );
        $main = $main->linkedTo(
            $third,
            static fn($edge) => $edge->asBidirectional(),
        );

        $graph = Graph::directed()->add($main);

        $output = $dot($graph);

        $expected = <<<DOT
digraph G {
    main -> second [label="watev", dir="none"];
    main -> third [dir="both"];
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testUndirectedGraph()
    {
        $dot = Dot::of();
        $main = Node::named('main')->linkedTo(Node::named('second'));

        $graph = Graph::undirected()->add($main);

        $output = $dot($graph);

        $expected = <<<DOT
graph G {
    main -- second;
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testNamedGraph()
    {
        $dot = Dot::of();

        $foo = Graph::directed('foo')->add(Node::named('main'));

        $output = $dot($foo);

        $expected = <<<DOT
digraph foo {
    main;
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testRenderClusters()
    {
        $first = Node::named('first');
        $second = Node::named('second');
        $third = Node::named('third');
        $first = $first->linkedTo(Node::named('first_inner'));
        $second = $second->linkedTo(Node::named('second_inner'));
        $third = $third->linkedTo(Node::named('third_inner'));

        $firstCluster = Graph::directed('first')
            ->displayAs('First')
            ->fillWithColor(Colour::yellow->toRGBA())
            ->colorizeBorderWith(Colour::green->toRGBA())
            ->add($first);

        $secondCluster = Graph::directed('second')->add($second);
        $thirdCluster = Graph::directed('third')->add($third);

        $root = Graph::directed()
            ->add(
                Node::named('start')
                    ->linkedTo($first)
                    ->linkedTo($second),
            )
            ->add(Node::named('first')->linkedTo(Node::named('third')))
            ->add(Node::named('second')->linkedTo(Node::named('third')))
            ->cluster($firstCluster)
            ->cluster($secondCluster)
            ->cluster($thirdCluster);

        $output = Dot::of()($root);

        $expected = <<<DOT
digraph G {
    subgraph cluster_first {
        label="First"
        style="filled"
        fillcolor="#ffff00"
        color="#008000"
    first -> first_inner;
    }
    subgraph cluster_second {
    second -> second_inner;
    }
    subgraph cluster_third {
    third -> third_inner;
    }
    start -> first;
    start -> second;
    first -> third;
    second -> third;
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testRenderGraphFromLeftToRight()
    {
        $root = Graph::directed('G', Rankdir::leftToRight);

        $output = Dot::of()($root);

        $expected = <<<DOT
digraph G {
    rankdir="LR";
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testRenderCyclicGraph()
    {
        $dot = Dot::of();
        $main = Node::named('main')->linkedTo($second = Node::named('second'));
        $second = $second->linkedTo($main);

        $graph = Graph::directed()
            ->add($main)
            ->add($second);

        $output = $dot($graph);

        $expected = <<<DOT
digraph G {
    main -> second;
    second -> main;
}
DOT;

        $this->assertSame($expected, $output->toString());
    }
}
