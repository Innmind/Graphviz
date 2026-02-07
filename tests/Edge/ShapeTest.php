<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Edge;

use Innmind\Graphviz\Edge\Shape;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    Set,
};

class ShapeTest extends TestCase
{
    use BlackBox;

    public function testShape(): BlackBox\Proof
    {
        return $this
            ->forAll($this->shapes())
            ->prove(function(string $shape): void {
                $this->assertInstanceOf(Shape::class, Shape::{$shape});
                $this->assertSame($shape, Shape::{$shape}->toString());
            });
    }

    public function shapes(): Set
    {
        return Set::of(
            'box',
            'crow',
            'curve',
            'icurve',
            'diamond',
            'dot',
            'inv',
            'none',
            'normal',
            'tee',
            'vee',
        );
    }
}
