# Graphviz

[![Build Status](https://github.com/innmind/graphviz/workflows/CI/badge.svg?branch=master)](https://github.com/innmind/graphviz/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/innmind/graphviz/branch/develop/graph/badge.svg)](https://codecov.io/gh/innmind/graphviz)
[![Type Coverage](https://shepherd.dev/github/innmind/graphviz/coverage.svg)](https://shepherd.dev/github/innmind/graphviz)

Graphviz model to help build graphs. This model goal is to express the possibilities offered by Graphviz (though note that all features are not implemented).

All objects of this package are immutable.

**Important**: you must use [`vimeo/psalm`](https://packagist.org/packages/vimeo/psalm) to make sure you use this library correctly.

## Installation

```sh
composer require innmind/graphviz
```

## Usage

```php
use Innmind\Graphviz\{
    Layout\Dot,
    Graph,
    Node,
    Node\Shape,
};
use Innmind\Url\Url;
use Innmind\Colour\Colour;
use Innmind\OperatingSystem\Factory;
use Innmind\Server\Control\Server\Command;

$dot = Dot::of();
$clusterOne = Graph::directed('one')
    ->target(Url::of('http://example.com'))
    ->displayAs('One')
    ->fillWithColor(Colour::blue->toRGBA())
    ->add($one = Node::named('one'));
$clusterTwo = Graph::directed('two')
    ->fillWithColor(Colour::red->toRGBA())
    ->add($two = Node::named('two'));
$clusterThree = Graph::directed('three')
    ->add($three = Node::named('three'));

$root = Node::named('root')
    ->shaped(Shape::house())
    ->linkedTo($one->name())
    ->linkedTo($two->name());

$graph = Graph::directed()
    ->add($root)
    ->add($one->linkedTo($three->name()))
    ->add($two->linkedTo($three->name()))
    ->cluster($clusterOne)
    ->cluster($clusterTwo)
    ->cluster($clusterThree);

$output = $dot($graph);

Factory::build()
    ->control()
    ->processes()
    ->execute(
        Command::foreground('dot')
            ->withShortOption('Tsvg')
            ->withShortOption('o', 'graph.svg')
            ->withInput($output),
    )
    ->wait();
```

This example will produce the given svg file: ([source](graph.dot))

![](graph.svg)

> [!NOTE]
> This example uses [`innmind/operating-system`](https://packagist.org/packages/innmind/operating-system).
