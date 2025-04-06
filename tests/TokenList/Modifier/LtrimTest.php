<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Modifier;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Ltrim;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LtrimTest extends TestCase
{
    #[Test]
    public function it_ltrims_specified_character(): void
    {
        $tokens = Tokens::create(new Tokenizer('   Some leading spaces'));

        $tokens = $tokens->modify(new Ltrim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Some leading spaces', $output);
    }

    #[Test]
    public function it_does_not_change_the_tokens_if_left_token_is_not_matched_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('No leading spaces'));

        $tokens = $tokens->modify(new Ltrim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('No leading spaces', $output);
    }
}
