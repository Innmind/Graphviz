<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Attribute;

use Innmind\Graphviz\{
    Attribute\Value,
    Exception\DomainException
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class ValueTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set\Decorate::immutable(
                static fn($chars) => \implode('', $chars),
                Set\Sequence::of(
                    Set\Unicode::any()->filter(static fn($s) => $s !== "\x00"),
                ),
            ))
            ->then(function(string $string): void {
                $this->assertSame($string, (new Value($string))->toString());
            });
    }

    public function testThrowWhenItContainsANullCharacter()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("foo\x00bar");

        new Value("foo\x00bar");
    }
}
