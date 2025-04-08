<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Navigator;

use Hike\TokenList\Navigator\SkipOver;
use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SkipOverTest extends TestCase
{
    #[Test]
    public function it_skips_over_tokens_of_supplied_types(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $tokens = $tokens->navigate(new SkipOver(Token::Word));

        $this->assertSame(Token::Space, $tokens->current()->name);
        $this->assertSame('look', $tokens->navigate(new Next())->current()->value);

        $tokens = $tokens->navigate(new SkipOver(Token::Word, Token::Space));

        $this->assertNull($tokens);
    }
}
