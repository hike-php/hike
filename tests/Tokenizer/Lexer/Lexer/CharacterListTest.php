<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Lexer\Lexer;

use Hike\Tokenizer\Character\Tokenizer;
use Hike\Tokenizer\Lexer\Lexer\CharacterList;
use Hike\Tokenizer\Lexer\Lexer\CharacterListMode;
use Hike\Tokenizer\Word\Token;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CharacterListTest extends TestCase
{
    #[Test]
    public function it_matches_a_list_of_characters_inclusive(): void
    {
        $tokens = Tokens::create(new Tokenizer('i love my brick'));

        $fixture = new CharacterList(Token::Word, range('a', 'z'), CharacterListMode::Inclusive);

        $tokens = $tokens->navigate(new Next('l'));

        $token = $fixture->match($tokens);

        $this->assertNotNull($token);
        $this->assertSame(Token::Word, $token->name);
        $this->assertSame('love', $token->value);
    }

    #[Test]
    public function it_matches_a_list_of_characters_exclusive(): void
    {
        $tokens = Tokens::create(new Tokenizer('i love my brick'));

        $fixture = new CharacterList(Token::Space, range('a', 'z'), CharacterListMode::Exclusive);

        $tokens = $tokens->navigate(new Next(' '));

        $token = $fixture->match($tokens);

        $this->assertNotNull($token);
        $this->assertSame(Token::Space, $token->name);
        $this->assertSame(' ', $token->value);
    }

    #[Test]
    public function it_returns_null_if_the_current_character_does_not_match(): void
    {
        $tokens = Tokens::create(new Tokenizer('i love my brick'));

        $fixture = new CharacterList(Token::Space, range('a', 'z'), CharacterListMode::Exclusive);

        $token = $fixture->match($tokens);

        $this->assertNull($token);
    }
}
