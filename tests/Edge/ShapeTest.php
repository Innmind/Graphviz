<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Edge;

use Innmind\Graphviz\Edge\Shape;
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait
};

class ShapeTest extends TestCase
{
    use TestTrait;

    public function testShape()
    {
        $this
            ->forAll($this->shapes())
            ->then(function(string $shape): void {
                $this->assertInstanceOf(Shape::class, Shape::$shape());
                $this->assertSame($shape, (string) Shape::$shape());
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
                $this->assertSame('o'.$shape, (string) $shape2);
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
                $this->assertSame('l'.$shape, (string) $shape2);
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
                $this->assertSame('r'.$shape, (string) $shape2);
            });
    }

    public function testSidesAreExclusive()
    {
        $this
            ->minimumEvaluationRatio(0.1)
            ->forAll(
                $this->shapes(),
                Generator\elements('right', 'left'),
                Generator\elements('right', 'left')
            )
            ->when(static function(string $shape, string $side1, string $side2): bool {
                return $side1 !== $side2;
            })
            ->then(function(string $shape, string $side1, string $side2): void {
                $side = substr($side2, 0, 1);

                $this->assertSame(
                    $side.$shape,
                    (string) Shape::$shape()
                        ->$side1()
                        ->$side2()
                );
            });
    }

    public function testSidesAreCombinableWithOpeness()
    {
        $this
            ->forAll(
                $this->shapes(),
                Generator\elements('right', 'left')
            )
            ->then(function(string $shape, string $side): void {
                $sideChar = substr($side, 0, 1);

                $this->assertSame(
                    'o'.$sideChar.$shape,
                    (string) Shape::$shape()
                        ->open()
                        ->$side()
                );
            });
    }

    public function shapes(): Generator
    {
        return Generator\elements(
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
            'vee'
        );
    }
}
