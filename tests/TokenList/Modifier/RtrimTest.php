<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Modifier;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Rtrim;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RtrimTest extends TestCase
{
    #[Test]
    public function it_rtrims_specified_character(): void
    {
        $tokens = Tokens::create(new Tokenizer('Some trailing spaces   '));

        $tokens = $tokens->modify(new Rtrim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Some trailing spaces', $output);
    }

    #[Test]
    public function it_does_not_change_the_tokens_if_right_token_is_not_matched_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('No trailing spaces'));

        $tokens = $tokens->modify(new Rtrim(Token::Space));

        $output = $tokens->output(new Serialize());

        $this->assertSame('No trailing spaces', $output);
    }
}
