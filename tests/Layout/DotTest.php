<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Layout;

use Innmind\Graphviz\{
    Layout\Dot,
    Node\Node
};
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
        $main = new Node('main');
        $parse = new Node('parse');
        $execute = new Node('execute');
        $makeString = new Node('make_string');
        $compare = new Node('compare');
        $printf = new Node('printf');
        $init = new Node('init');
        $cleanup = new Node('cleanup');

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
}
