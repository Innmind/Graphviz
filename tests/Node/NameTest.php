<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node\Name,
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

    public function testInterface()
    {
        $this
            ->forAll(Set::strings())
            ->filter(static function(string $string): bool {
                return \strlen($string) > 0 && \strpos($string, '->') === false && \strpos($string, '-') === false && \strpos($string, '.') === false && \strpos($string, "\x00") === false;
            })
            ->then(function(string $string): void {
                $this->assertSame($string, Name::of($string)->toString());
            });
    }

    public function testThrowWhenContainingAnArrow()
    {
        $this->expectException(DomainException::class);

        Name::of('->');
    }

    public function testThrowWhenContainingAnDash()
    {
        $this->expectException(DomainException::class);

        Name::of('-');
    }

    public function testThrowWhenContainingADot()
    {
        $this->expectException(DomainException::class);

        Name::of('.');
    }

    public function testThrowWhenContainingANullCharacter()
    {
        $this->expectException(DomainException::class);

        Name::of("\x00");
    }
}
