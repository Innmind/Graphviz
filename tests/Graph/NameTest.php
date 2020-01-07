<?php
declare(strict_types = 1);

namespace Tests\Innmind\Graphviz\Graph;

use Innmind\Graphviz\{
    Graph\Name,
    Exception\DomainException
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait
};

class NameTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\string())
            ->when(static function(string $string): bool {
                return (bool) preg_match('~[a-zA-Z0-9_]+~', $string);
            })
            ->then(function(string $string): void {
                $this->assertSame($string, (new Name($string))->toString());
            });
    }

    public function testThrowWhenContainingInvalidCharacters()
    {
        $this
            ->forAll(Generator\string())
            ->when(static function(string $string): bool {
                return !preg_match('~[a-zA-Z0-9_]+~', $string);
            })
            ->then(function(string $string): void {
                $this->expectException(DomainException::class);

                new Name($string);
            });
    }
}
