<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Graph;

use Innmind\Graphviz\Graph\Rankdir;
use PHPUnit\Framework\TestCase;

class RankdirTest extends TestCase
{
    public function testInterface()
    {
        $tb = Rankdir::topToBottom();
        $lr = Rankdir::leftToRight();

        $this->assertInstanceOf(Rankdir::class, $tb);
        $this->assertInstanceOf(Rankdir::class, $lr);
        $this->assertSame('TB', $tb->toString());
        $this->assertSame('LR', $lr->toString());
        $this->assertSame($tb, Rankdir::topToBottom());
        $this->assertSame($lr, Rankdir::leftToRight());
    }
}
