<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Token;

use Hike\Tokenizer\Token\Token;
use Hike\Tokenizer\Word\Token as WordToken;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TokenTest extends TestCase
{
    #[Test]
    public function it_can_identify_a_token_by_type(): void
    {
        $token = new Token(
            WordToken::Space,
            ' ',
        );

        $this->assertTrue($token->is(WordToken::Space));
        $this->assertFalse($token->is(WordToken::Word));
        $this->assertTrue($token->is(' '));
        $this->assertTrue($token->is(WordToken::Word, WordToken::Space));
        $this->assertTrue($token->is('abc', ' '));
    }
}
