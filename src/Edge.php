<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

interface Edge
{
    public function from(): Node;
    public function to(): Node;
}
