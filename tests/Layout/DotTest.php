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

        $parse->linkedTo($execute);
        $main->linkedTo($parse);
        $main->linkedTo($init);
        $main->linkedTo($cleanup);
        $execute->linkedTo($makeString);
        $execute->linkedTo($printf);
        $init->linkedTo($makeString);
        $main->linkedTo($printf);
        $execute->linkedTo($compare);

        $graph = Graph::directed();
        $graph->add($main);
        $graph->add($parse);
        $graph->add($execute);
        $graph->add($init);

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

        $graph = Graph::directed();
        $graph->add(Node::of(Name::of('main')));

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

        $parse->linkedTo($execute);
        $main->linkedTo($parse);
        $main->linkedTo($init);
        $main->linkedTo($cleanup);
        $execute->linkedTo($makeString);
        $execute->linkedTo($printf);
        $init->linkedTo($makeString);
        $main->linkedTo($printf);
        $execute->linkedTo($compare);
        $main->shaped(Shape::circle());
        $main->displayAs('Main Node');
        $main->target(Url::of('example.com'));
        $parse->displayAs('Parse');

        $graph = Graph::directed();
        $graph->add($main);
        $graph->add($parse);
        $graph->add($execute);
        $graph->add($init);

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
        $main->linkedTo(
            $second,
            static fn($edge) => $edge
                ->displayAs('watev')
                ->withoutDirection(),
        );
        $main->linkedTo(
            $third,
            static fn($edge) => $edge->asBidirectional(),
        );

        $graph = Graph::directed();
        $graph->add($main);

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
        $main = Node::named('main');
        $main->linkedTo(Node::named('second'));

        $graph = Graph::undirected();
        $graph->add($main);

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

        $foo = Graph::directed('foo');
        $foo->add(Node::named('main'));

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
        $root = Graph::directed();
        $firstCluster = Graph::directed('first');
        $firstCluster->displayAs('First');
        $firstCluster->fillWithColor(Colour::yellow->toRGBA());
        $firstCluster->colorizeBorderWith(Colour::green->toRGBA());
        $secondCluster = Graph::directed('second');
        $thirdCluster = Graph::directed('third');

        $start = Node::named('start');
        $first = Node::named('first');
        $second = Node::named('second');
        $third = Node::named('third');

        $start->linkedTo($first);
        $start->linkedTo($second);
        $first->linkedTo($third);
        $second->linkedTo($third);

        $root->add($start);
        $root->add($first);
        $root->add($second);
        $root->cluster($firstCluster);
        $root->cluster($secondCluster);
        $root->cluster($thirdCluster);

        $first = Node::named('first');
        $second = Node::named('second');
        $third = Node::named('third');
        $first->linkedTo(Node::named('first_inner'));
        $second->linkedTo(Node::named('second_inner'));
        $third->linkedTo(Node::named('third_inner'));

        $firstCluster->add($first);
        $secondCluster->add($second);
        $thirdCluster->add($third);

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
        $main = Node::named('main');
        $main->linkedTo($second = Node::named('second'));
        $second->linkedTo($main);

        $graph = Graph::directed();
        $graph->add($main);
        $graph->add($second);

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
