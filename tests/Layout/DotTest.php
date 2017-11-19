<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Layout\Dot,
    Layout\Size,
    Node\Node,
    Node\Shape,
    Node\Name
};
use Innmind\Url\Url;
use Innmind\Immutable\Str;
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

        $output = $layout($main);

        $expected = <<<DOT
digraph G {
    main -> parse -> execute;
    main -> init -> make_string;
    main -> cleanup;
    main -> printf;
    execute -> make_string;
    execute -> printf;
    execute -> compare;
}
DOT;

        $this->assertInstanceOf(Str::class, $output);
        $this->assertSame($expected, (string) $output);
    }

    public function testSize()
    {
        $dot = new Dot(new Size(2, 4));

        $output = $dot(new Node(new Name('main')));
        $expected = <<<DOT
digraph G {
    size = "2,4";
    main;
}
DOT;

        $this->assertSame($expected, (string) $output);
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
        $main
            ->shaped(Shape::circle())
            ->displayAs('Main Node')
            ->target(Url::fromString('example.com'));
        $parse->displayAs('Parse');

        $output = $dot($main);

        $expected = <<<DOT
digraph G {
    main -> parse -> execute;
    main -> init -> make_string;
    main -> cleanup;
    main -> printf;
    execute -> make_string;
    execute -> printf;
    execute -> compare;

    main [shape="circle", label="Main Node", target="example.com"];
    parse [label="Parse"];
}
DOT;

        $this->assertSame($expected, (string) $output);
    }
}
