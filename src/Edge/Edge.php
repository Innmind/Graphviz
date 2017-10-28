<?php
declare(strict_types = 1);

namespace Innmind\Graphviz\Edge;

use Innmind\Graphviz\{
    Edge as EdgeInterface,
    Node
};

final class Edge implements EdgeInterface
{
    private $from;
    private $to;

    public function __construct(Node $from, Node $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function from(): Node
    {
        return $this->from;
    }

    public function to(): Node
    {
        return $this->to;
    }
}
