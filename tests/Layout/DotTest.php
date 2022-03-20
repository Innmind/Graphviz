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
        $layout = new Dot;
        $main = new Node(new Name('main'));
        $parse = new Node(new Name('parse'));
        $execute = new Node(new Name('execute'));
        $makeString = new Node(new Name('make_string'));
        $compare = new Node(new Name('compare'));
        $printf = new Node(new Name('printf'));
        $init = new Node(new Name('init'));
        $cleanup = new Node(new Name('cleanup'));

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
        $dot = new Dot(new DPI(200));

        $graph = Graph::directed();
        $graph->add(new Node(new Name('main')));

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
        $dot = new Dot;

        $main = new Node(new Name('main'));
        $parse = new Node(new Name('parse'));
        $execute = new Node(new Name('execute'));
        $makeString = new Node(new Name('make_string'));
        $compare = new Node(new Name('compare'));
        $printf = new Node(new Name('printf'));
        $init = new Node(new Name('init'));
        $cleanup = new Node(new Name('cleanup'));

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
        $dot = new Dot;

        $main = new Node(new Name('main'));
        $second = new Node(new Name('second'));
        $third = new Node(new Name('third'));
        $edge = $main->linkedTo($second);
        $edge->displayAs('watev');
        $edge->withoutDirection();
        $main
            ->linkedTo($third)
            ->asBidirectional();

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
        $dot = new Dot;
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
        $dot = new Dot;

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

        $output = (new Dot)($root);

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

        $output = (new Dot)($root);

        $expected = <<<DOT
digraph G {
    rankdir="LR";
}
DOT;

        $this->assertSame($expected, $output->toString());
    }

    public function testRenderCyclicGraph()
    {
        $dot = new Dot;
        $main = Node::named('main');
        $main->linkedTo($second = Node::named('second'));
        $second->linkedTo($main);

        $graph = Graph::directed();
        $graph->add($main);

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
