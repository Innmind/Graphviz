<?php
declare(strict_types = 1);

namespace Innmind\Graphviz;

use Innmind\Immutable\SetInterface;

interface Node
{
    public function name(): string;

    /**
     * @return SetInterface<Edge>
     */
    public function edges(): SetInterface;
    public function linkedTo(self $node): Edge;
}
