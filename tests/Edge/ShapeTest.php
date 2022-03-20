<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Edge;

use Innmind\Graphviz\Edge\Shape;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class ShapeTest extends TestCase
{
    use BlackBox;

    public function testShape()
    {
        $this
            ->forAll($this->shapes())
            ->then(function(string $shape): void {
                $this->assertInstanceOf(Shape::class, Shape::$shape());
                $this->assertSame($shape, Shape::$shape()->toString());
            });
    }

    public function testOpen()
    {
        $this
            ->forAll($this->shapes())
            ->then(function(string $shape): void {
                $shape = Shape::$shape();
                $shape2 = $shape->open();

                $this->assertNotSame($shape, $shape2);
                $this->assertSame('o'.$shape->toString(), $shape2->toString());
            });
    }

    public function testLeft()
    {
        $this
            ->forAll($this->shapes())
            ->then(function(string $shape): void {
                $shape = Shape::$shape();
                $shape2 = $shape->left();

                $this->assertNotSame($shape, $shape2);
                $this->assertSame('l'.$shape->toString(), $shape2->toString());
            });
    }

    public function testRight()
    {
        $this
            ->forAll($this->shapes())
            ->then(function(string $shape): void {
                $shape = Shape::$shape();
                $shape2 = $shape->right();

                $this->assertNotSame($shape, $shape2);
                $this->assertSame('r'.$shape->toString(), $shape2->toString());
            });
    }

    public function testSidesAreExclusive()
    {
        $this
            ->forAll(
                $this->shapes(),
                Set\Elements::of('right', 'left'),
                Set\Elements::of('right', 'left'),
            )
            ->filter(static function(string $shape, string $side1, string $side2): bool {
                return $side1 !== $side2;
            })
            ->then(function(string $shape, string $side1, string $side2): void {
                $side = \substr($side2, 0, 1);

                $this->assertSame(
                    $side.$shape,
                    Shape::$shape()
                        ->$side1()
                        ->$side2()
                        ->toString(),
                );
            });
    }

    public function testSidesAreCombinableWithOpeness()
    {
        $this
            ->forAll(
                $this->shapes(),
                Set\Elements::of('right', 'left'),
            )
            ->then(function(string $shape, string $side): void {
                $sideChar = \substr($side, 0, 1);

                $this->assertSame(
                    'o'.$sideChar.$shape,
                    Shape::$shape()
                        ->open()
                        ->$side()
                        ->toString(),
                );
            });
    }

    public function shapes(): Set
    {
        return Set\Elements::of(
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
