<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Lexer\Lexer;

use Hike\Tokenizer\Character\Tokenizer;
use Hike\Tokenizer\Lexer\Lexer\Word;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordTest extends TestCase
{
    #[Test]
    public function it_matches_a_list_of_characters_inclusive(): void
    {
        $tokens = Tokens::create(new Tokenizer('i love my brick'));

        $fixture = new Word(Words::Pronoun, 'my');

        $tokens = $tokens->navigate(new Next('m'));

        $this->assertNotNull($tokens);

        $token = $fixture->match($tokens);

        $this->assertNotNull($token);
        $this->assertSame(Words::Pronoun, $token->name);
        $this->assertSame('my', $token->value);
    }
}
