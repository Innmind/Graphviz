<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Attribute;

use Innmind\Graphviz\{
    Attribute\Value,
    Exception\DomainException
};
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    Set,
};

class ValueTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(
                Set::sequence(
                    Set::strings()->unicode()->char()->filter(static fn($s) => $s !== "\x00"),
                )->map(static fn($chars) => \implode('', $chars)),
            )
            ->then(function(string $string): void {
                $this->assertSame($string, Value::of($string)->toString());
            });
    }

    public function testThrowWhenItContainsANullCharacter()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("foo\x00bar");

        Value::of("foo\x00bar");
    }
}
