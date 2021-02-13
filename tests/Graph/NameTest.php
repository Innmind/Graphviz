<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Graph;

use Innmind\Graphviz\{
    Graph\Name,
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
                return (bool) \preg_match('~[a-zA-Z0-9_]+~', $string);
            })
            ->then(function(string $string): void {
                $this->assertSame($string, (new Name($string))->toString());
            });
    }

    public function testThrowWhenContainingInvalidCharacters()
    {
        $this
            ->forAll(Set\Strings::any())
            ->filter(static function(string $string): bool {
                return !\preg_match('~[a-zA-Z0-9_]+~', $string);
            })
            ->then(function(string $string): void {
                $this->expectException(DomainException::class);

                new Name($string);
            });
    }
}
