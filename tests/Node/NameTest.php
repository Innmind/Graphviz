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

    public function testInterface(): BlackBox\Proof
    {
        return $this
            ->forAll(Set::strings())
            ->filter(static function(string $string): bool {
                return \strlen($string) > 0 && \strpos($string, '->') === false && \strpos($string, '-') === false && \strpos($string, '.') === false && \strpos($string, "\x00") === false;
            })
            ->prove(function(string $string): void {
                $this->assertSame($string, Name::of($string)->toString());
            });
    }

    public function testThrowWhenContainingAnArrow()
    {
        $this->assert()->throws(
            static fn() => Name::of('->'),
            DomainException::class,
        );
    }

    public function testThrowWhenContainingAnDash()
    {
        $this->assert()->throws(
            static fn() => Name::of('-'),
            DomainException::class,
        );
    }

    public function testThrowWhenContainingADot()
    {
        $this->assert()->throws(
            static fn() => Name::of('.'),
            DomainException::class,
        );
    }

    public function testThrowWhenContainingANullCharacter()
    {
        $this->assert()->throws(
            static fn() => Name::of("\x00"),
            DomainException::class,
        );
    }
}
