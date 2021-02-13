<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Node;

use Innmind\Graphviz\{
    Node\Name,
    Exception\DomainException
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class NameTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set\Strings::any())
            ->filter(static function(string $string): bool {
                return \strlen($string) > 0 && \strpos($string, '->') === false && \strpos($string, '-') === false && \strpos($string, '.') === false && \strpos($string, "\x00") === false;
            })
            ->then(function(string $string): void {
                $this->assertSame($string, (new Name($string))->toString());
            });
    }

    public function testThrowWhenEmpty()
    {
        $this->expectException(DomainException::class);

        new Name('');
    }

    public function testThrowWhenContainingAnArrow()
    {
        $this->expectException(DomainException::class);

        new Name('->');
    }

    public function testThrowWhenContainingAnDash()
    {
        $this->expectException(DomainException::class);

        new Name('-');
    }

    public function testThrowWhenContainingADot()
    {
        $this->expectException(DomainException::class);

        new Name('.');
    }

    public function testThrowWhenContainingANullCharacter()
    {
        $this->expectException(DomainException::class);

        new Name("\x00");
    }
}
