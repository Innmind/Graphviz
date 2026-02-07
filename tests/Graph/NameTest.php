<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Graph;

use Innmind\Graphviz\{
    Graph\Name,
    Exception\DomainException
};
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    Set,
};

class NameTest extends TestCase
{
    use BlackBox;

    public function testInterface(): BlackBox\Proof
    {
        return $this
            ->forAll(Set::strings())
            ->filter(static function(string $string): bool {
                return (bool) \preg_match('~[a-zA-Z0-9_]+~', $string);
            })
            ->prove(function(string $string): void {
                $this->assertSame($string, Name::of($string)->toString());
            });
    }

    public function testThrowWhenContainingInvalidCharacters(): BlackBox\Proof
    {
        return $this
            ->forAll(Set::strings())
            ->filter(static function(string $string): bool {
                return !\preg_match('~[a-zA-Z0-9_]+~', $string);
            })
            ->prove(function(string $string): void {
                $this->expectException(DomainException::class);

                $_ = Name::of($string);
            });
    }
}
