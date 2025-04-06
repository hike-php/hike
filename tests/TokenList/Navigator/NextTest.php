<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Navigator;

use Hike\Tokenizer\Word\Token;
use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\End;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NextTest extends TestCase
{
    #[Test]
    public function it_can_repeatedly_move_to_the_next_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $this->assertSame('Ah,', $tokens->current()?->value);
        $this->assertSame(Token::Word, $tokens->current()->name);

        $tokens = $tokens->navigate(new Next());

        $this->assertSame(Token::Space, $tokens?->current()?->name);

        $tokens = $tokens->navigate(new Next());

        $this->assertSame(Token::Word, $tokens?->current()?->name);
        $this->assertSame('look', $tokens->current()->value);

        $tokens = $tokens->navigate(new Next());

        $this->assertSame(Token::Space, $tokens?->current()?->name);
    }

    #[Test]
    public function it_moves_to_the_next_token_it_finds_from_supplied_list(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $tokens = $tokens->navigate(new Next('him', 'his', 'look'));

        $this->assertNotNull($tokens);
        $this->assertSame(Token::Word, $tokens->current()?->name);
        $this->assertSame('look', $tokens->current()->value);
    }

    #[Test]
    public function it_moves_to_the_next_token_it_finds_from_supplied_list_regardless_of_argument_order(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $tokens = $tokens->navigate(new Next('look', 'him', 'his'));

        $this->assertNotNull($tokens);
        $this->assertSame(Token::Word, $tokens->current()?->name);
        $this->assertSame('look', $tokens->current()->value);
    }

    #[Test]
    public function it_cannot_navigate_to_a_token_that_does_not_exist(): void
    {
        $tokens = Tokens::create(new Tokenizer('NoSpacesHere'));

        $tokens = $tokens->navigate(new Next(Token::Space));

        $this->assertNull($tokens);
    }

    #[Test]
    public function it_cannot_navigate_to_a_token_which_exists_before_the_current_position(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new Next('look'));
        $this->assertNotNull($tokens);

        $tokens = $tokens->navigate(new Next(), new Next('look'));
        $this->assertNull($tokens);
    }

    #[Test]
    public function it_cannot_navigate_beyond_the_end_of_the_tokens(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));
        $tokens = $tokens->navigate(new End());
        $this->assertNotNull($tokens);
        $tokens = $tokens->navigate(new Next());

        $this->assertNull($tokens);
    }

    #[Test]
    public function it_can_chain_calls_to_move_through_the_string(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $tokens = $tokens->navigate(
            new Next('at'),
            new Next('with'),
            new Next('hairy'),
            new Next(),
            new Next(),
        );

        $this->assertNotNull($tokens);
        $this->assertSame('hands!', $tokens->current()?->value);
    }

    #[Test]
    public function it_can_navigate_to_the_next_token_by_type_or_value(): void
    {
        $tokens = Tokens::create(new Tokenizer('Ah, look at him there with his hairy hands!'));

        $tokens = $tokens->navigate(
            new Next('look', Token::Space),
        );

        $this->assertNotNull($tokens);
        $this->assertSame(Token::Space, $tokens->current()?->name);
    }
}
