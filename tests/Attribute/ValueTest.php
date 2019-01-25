<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Attribute;

use Innmind\Graphviz\{
    Attribute\Value,
    Exception\DomainException
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait
};

class ValueTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\string())
            ->then(function(string $string): void {
                $this->assertSame($string, (string) new Value($string));
            });
    }

    public function testThrowWhenItContainsANullCharacter()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("foo\x00bar");

        new Value("foo\x00bar");
    }
}
