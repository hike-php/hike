<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Modifier;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Insert;
use Hike\TokenList\Modifier\InsertMode;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InsertTest extends TestCase
{
    #[Test]
    public function it_inserts_before_cursor_position_in_insert_mode(): void
    {
        $tokens = Tokens::create(new Tokenizer('Oh you\'re there, Ted'));

        $tokensToInsert = Tokens::create(new Tokenizer(' right'));

        $tokens = $tokens->navigate(
            new Next(Token::Space),
            new Next(Token::Space),
        );

        $this->assertNotNull($tokens);
        $tokens = $tokens->modify(new Insert($tokensToInsert));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Oh you\'re right there, Ted', $output);
    }

    #[Test]
    public function it_inserts_after_cursor_position_in_append_mode(): void
    {
        $tokens = Tokens::create(new Tokenizer('Oh you\'re there, Ted'));

        $tokensToInsert = Tokens::create(new Tokenizer('right '));

        $tokens = $tokens->navigate(
            new Next(Token::Space),
            new Next(Token::Space),
        );

        $this->assertNotNull($tokens);
        $tokens = $tokens->modify(new Insert($tokensToInsert, InsertMode::Append));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Oh you\'re right there, Ted', $output);
    }

    #[Test]
    public function it_can_insert_into_an_existing_empty_token_set(): void
    {
        $tokens = Tokens::create(new Tokenizer(''));

        $tokensToInsert = Tokens::create(new Tokenizer('No round envelopes for me'));

        $tokens = $tokens->modify(
            new Insert($tokensToInsert, InsertMode::Insert),
        );

        $output = $tokens->output(new Serialize());

        $this->assertSame('No round envelopes for me', $output);
    }

    #[Test]
    public function it_can_append_onto_an_existing_empty_token_set(): void
    {
        $tokens = Tokens::create(new Tokenizer(''));

        $tokensToInsert = Tokens::create(new Tokenizer('No round envelopes for me'));

        $tokens = $tokens->modify(
            new Insert($tokensToInsert, InsertMode::Append),
        );

        $output = $tokens->output(new Serialize());

        $this->assertSame('No round envelopes for me', $output);
    }
}
