# Graphviz

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Graphviz/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Graphviz/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Graphviz/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Graphviz/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Graphviz/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Graphviz/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Graphviz/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Graphviz/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/Graphviz/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Graphviz/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/Graphviz/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Graphviz/build-status/develop) |

Graphviz model to help build graphs. This model goal is to express the possibilities offered by Graphviz (though note that all features are not implemented).

## Installation

```sh
composer require innmind/graphviz
```

## Usage

```php
use Innmind\Graphviz\{
    Layout\Dot,
    Graph\Graph,
    Node\Node,
    Node\Shape
};
use Innmind\Url\Url;
use Innmind\Colour\Colour;
use Innmind\Server\Control\{
    ServerFactory,
    Server\Command
};
use Innmind\Filesystem\Stream\StringStream;

$dot = new Dot;
$graph = Graph::directed();
$clusterOne = Graph::directed('one')
    ->target(Url::fromString('http://example.com'))
    ->displayAs('One')
    ->fillWithColor(Colour::fromString('blue'))
    ->add(Node::named('one'));
$clusterTwo = Graph::directed('two')
    ->fillWithColor(Colour::fromString('red'))
    ->add(Node::named('two'));
$clusterThree = Graph::directed('three')
    ->add($three = Node::named('three'));

//important to not reuse nodes added in clusters otherwise clusters boundaries
//will be messed up
$root = Node::named('root')
    ->shaped(Shape::house());
$root->linkedTo($one = Node::named('one'));
$root->linkedTo($two = Node::named('two'));
$one->linkedTo($three);
$two->linkedTo($three);

$graph
    ->add($root)
    ->cluster($clusterOne)
    ->cluster($clusterTwo)
    ->cluster($clusterThree);

$output = $dot($graph);

(new ServerFactory)
    ->make()
    ->processes()
    ->execute(
        Command::foreground('dot')
            ->withShortOption('Tsvg')
            ->withShortOption('o', 'graph.svg')
            ->withInput(new StringStream((string) $output))
    )
    ->wait();
```

This example will produce the given svg file: ([source](graph.dot))

![](graph.svg)
