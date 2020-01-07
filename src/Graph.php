<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Graph\Name;
use Innmind\Colour\RGBA;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};

interface Graph
{
    public function isDirected(): bool;
    public function name(): Name;
    public function cluster(self $cluster): void;

    /**
     * @return SetInterface<self>
     */
    public function clusters(): SetInterface;
    public function add(Node $node): void;

    /**
     * @return SetInterface<Node>
     */
    public function roots(): SetInterface;

    /**
     * @return SetInterface<Node>
     */
    public function nodes(): SetInterface;
    public function displayAs(string $label): void;
    public function fillWithColor(RGBA $color): void;
    public function colorizeBorderWith(RGBA $color): void;
    public function target(UrlInterface $url): void;

    /**
     * @return MapInterface<string, string>
     */
    public function attributes(): MapInterface;
}
