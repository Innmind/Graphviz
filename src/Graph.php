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
    public function cluster(self $cluster): self;

    /**
     * @return SetInterface<self>
     */
    public function clusters(): SetInterface;
    public function add(Node $node): self;

    /**
     * @return SetInterface<Node>
     */
    public function roots(): SetInterface;

    /**
     * @return SetInterface<Node>
     */
    public function nodes(): SetInterface;
    public function displayAs(string $label): self;
    public function fillWithColor(RGBA $color): self;
    public function colorizeBorderWith(RGBA $color): self;
    public function target(UrlInterface $url): self;

    /**
     * @return MapInterface<string, string>
     */
    public function attributes(): MapInterface;
}
