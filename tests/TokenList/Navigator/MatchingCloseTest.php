<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Navigator;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\MatchingClose;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Navigator\Previous;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MatchingCloseTest extends TestCase
{
    #[Test]
    public function it_navigates_to_the_matching_outer_close_brace(): void
    {
        $tokens = $this->getTokens()->navigate(
            new Next('{'),
        );

        $this->assertNotNull($tokens);

        $tokens = $tokens->navigate(new MatchingClose());

        $this->assertNotNull($tokens);
        $this->assertSame('}', $tokens->current()?->value);

        $this->assertNull($tokens->navigate(new Next()));
    }

    #[Test]
    public function it_navigates_to_the_matching_inner_close_brace(): void
    {
        $tokens = $this->getTokens()->navigate(
            new Next('if'),
            new Next('{'),
        );

        $this->assertNotNull($tokens);

        $tokens = $tokens->navigate(new MatchingClose());

        $this->assertNotNull($tokens);
        $this->assertSame('}', $tokens->current()?->value);

        $this->assertSame('\'bar\';', $tokens->navigate(new Previous(Token::Word))?->current()?->value);
    }

    #[Test]
    public function it_navigates_to_the_matching_outer_close_bracket(): void
    {
        $tokens = $this->getTokens()->navigate(
            new Next('if'),
            new Next('('),
        );

        $this->assertNotNull($tokens);

        $tokens = $tokens->navigate(new MatchingClose());

        $this->assertNotNull($tokens);
        $this->assertSame(')', $tokens->current()?->value);

        $this->assertSame(
            '12',
            $tokens
                ->navigate(
                    new Previous(),
                    new Previous(),
                )
                ?->current()
                ?->value,
        );
    }

    #[Test]
    public function it_navigates_to_the_matching_inner_close_bracket(): void
    {
        $tokens = $this->getTokens()->navigate(
            new Next('rand'),
            new Next('('),
        );

        $this->assertNotNull($tokens);

        $tokens = $tokens->navigate(new MatchingClose());

        $this->assertNotNull($tokens);
        $this->assertSame(')', $tokens->current()?->value);

        $this->assertSame(
            '(',
            $tokens
                ->navigate(
                    new Previous(),
                    new Previous(),
                )
                ?->current()
                ?->value,
        );
    }

    #[Test]
    public function it_throws_if_there_is_no_matching_close_character(): void
    {
        $tokens = Tokens::create(
            new Tokenizer(<<<'EOT'
            Here's an open for an unconfigured close

            :


            there is no close
            EOT),
        );

        $this->expectException(\RuntimeException::class);

        $tokens = $tokens->navigate(new Next(':'), new MatchingClose());
    }

    private function getTokens(): Tokens
    {
        return Tokens::create(new Tokenizer(<<<'EOT'
            <?php

            declare(strict_types=1);

            namespace Test;

            class Foo {

                // Intentionally messed up formatting so
                // that brackets are their own tokens 
                public function __construct ( ) {

                }

                private function bar ( ) {
                    echo '123';
                    echo '234';
                    if ( rand ( ) === 12 ) {
                        $foo = 'bar';
                    }
                    $contents = \file_get_contents(\realpath('./file.txt'));
                }
            }
            EOT));
    }
}
