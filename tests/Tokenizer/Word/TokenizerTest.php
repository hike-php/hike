<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Word;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    #[Test]
    public function it_tokenizes_a_string_into_words_and_spaces(): void
    {
        $originalString = 'Down with this sort of thing';

        $tokens = Tokens::create(new Tokenizer($originalString));

        $this->assertSame('Down', $tokens->current()?->value);
        $this->assertSame(Token::Word, $tokens->current()->name);

        $tokens = $tokens->navigate(new Next());

        $this->assertSame(' ', $tokens?->current()?->value);
        $this->assertSame(Token::Space, $tokens->current()->name);

        $tokens = $tokens->navigate(new Next());

        $this->assertSame('with', $tokens?->current()?->value);
        $this->assertSame(Token::Word, $tokens->current()->name);

        $this->assertSame($originalString, $tokens->output(new Serialize()));
    }
}
