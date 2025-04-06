<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Modifier;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Trim;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TrimTest extends TestCase
{
    #[Test]
    public function it_trims_specified_character(): void
    {
        $tokens = Tokens::create(new Tokenizer('   Leading and Trailing spaces   '));

        $tokens = $tokens->modify(new Trim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Leading and Trailing spaces', $output);
    }

    #[Test]
    public function it_ltrims_specified_character(): void
    {
        $tokens = Tokens::create(new Tokenizer('   Leading spaces'));

        $tokens = $tokens->modify(new Trim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Leading spaces', $output);
    }

    #[Test]
    public function it_rtrims_specified_character(): void
    {
        $tokens = Tokens::create(new Tokenizer('Trailing spaces    '));

        $tokens = $tokens->modify(new Trim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Trailing spaces', $output);
    }


    #[Test]
    public function it_does_not_change_the_tokens_if_right_token_is_not_matched_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('No leading or trailing spaces'));

        $tokens = $tokens->modify(new Trim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('No leading or trailing spaces', $output);
    }
}
