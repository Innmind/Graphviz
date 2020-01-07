# Graphviz

| `develop` |
|-----------|
| [![codecov](https://codecov.io/gh/Innmind/Graphviz/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/Graphviz) |
| [![Build Status](https://github.com/Innmind/Graphviz/workflows/CI/badge.svg)](https://github.com/Innmind/Graphviz/actions?query=workflow%3ACI) |

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
            ->withInput($output)
    )
    ->wait();
```

This example will produce the given svg file: ([source](graph.dot))

![](graph.svg)
