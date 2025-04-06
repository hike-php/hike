<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Navigator;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\End;
use Hike\TokenList\Navigator\Previous;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PreviousTest extends TestCase
{
    #[Test]
    public function it_can_repeatedly_move_to_the_previous_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());

        $this->assertSame(Token::Word, $tokens?->current()->name);

        $tokens = $tokens->navigate(new Previous());

        $this->assertSame(Token::Space, $tokens?->current()->name);

        $tokens = $tokens->navigate(new Previous());

        $this->assertSame(Token::Word, $tokens?->current()->name);

        $tokens = $tokens->navigate(new Previous());

        $this->assertSame(Token::Space, $tokens?->current()->name);

        $tokens = $tokens->navigate(new Previous());
        $this->assertSame(Token::Word, $tokens?->current()->name);
        $this->assertSame('his', $tokens?->current()->value);
    }

    #[Test]
    public function it_moves_to_the_previous_token_it_finds_from_supplied_list(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());


        $tokens = $tokens?->navigate(new Previous('him', 'with'));

        $this->assertNotNull($tokens);
        $this->assertSame('with', $tokens->current()->value);
    }

    #[Test]
    public function it_moves_to_the_next_token_it_finds_from_supplied_list_regardless_of_argument_order(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());


        $tokens = $tokens?->navigate(new Previous('with', 'him'));

        $this->assertNotNull($tokens);
        $this->assertSame('with', $tokens->current()->value);
    }

    #[Test]
    public function it_cannot_navigate_to_a_token_that_does_not_exist(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());

        $tokens = $tokens?->navigate(new Previous('Hello'));

        $this->assertNull($tokens);
    }

    #[Test]
    public function it_cannot_navigate_beyond_the_start_of_the_tokens(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $tokens = $tokens?->navigate(new Previous());

        $this->assertNull($tokens);
    }

    #[Test]
    public function it_cannot_navigate_to_a_token_which_exists_after_the_current_position(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());

        $tokens = $tokens?->navigate(new Previous('with'));
        $this->assertNotNull($tokens);

        $tokens = $tokens->navigate(new Previous('his'));
        $this->assertNull($tokens);
    }

    #[Test]
    public function it_can_chain_calls_to_move_through_the_tokens(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());

        $tokens = $tokens?->navigate(
            new Previous('his'),
            new Previous('there'),
            new Previous('at'),
            new Previous(),
            new Previous(),
        );

        $this->assertNotNull($tokens);
        $this->assertSame('look', $tokens->current()->value);
    }

    #[Test]
    public function it_can_navigate_to_the_previous_token_by_type_or_value(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());

        $tokens = $tokens?->navigate(
            new Previous('look', Token::Space),
        );

        $this->assertNotNull($tokens);
        $this->assertSame(Token::Space, $tokens->current()->name);
    }
}
