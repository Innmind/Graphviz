<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Graphviz\Graph\Name;
use Innmind\Colour\RGBA;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Map,
};

interface Graph
{
    public function isDirected(): bool;
    public function name(): Name;
    public function cluster(self $cluster): void;

    /**
     * @return Set<self>
     */
    public function clusters(): Set;
    public function add(Node $node): void;

    /**
     * @return Set<Node>
     */
    public function roots(): Set;

    /**
     * @return Set<Node>
     */
    public function nodes(): Set;
    public function displayAs(string $label): void;
    public function fillWithColor(RGBA $color): void;
    public function colorizeBorderWith(RGBA $color): void;
    public function target(Url $url): void;

    /**
     * @return Map<string, string>
     */
    public function attributes(): Map;
}
