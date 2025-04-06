<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Lexer;

use Hike\Tokenizer\Lexer\Lexer\Word;
use Hike\Tokenizer\Lexer\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Hike\Tokenizer\Lexer\Lexer\Words;

final class TokenizerFunctionalTest extends TestCase
{
    #[Test]
    public function it_supports_configured_lexers(): void
    {
        $tokens = Tokenizer::fromString('I love my brick')
            ->addLexer(new Word(Words::Pronoun, 'I', 'my'))
            ->addLexer(new Word(Words::Verb, 'love'))
            ->addLexer(new Word(Words::Noun, 'brick'))
            ->addLexer(new Word(Words::Space, ' '))
            ->getTokens()
        ;

        $tokens = Tokens::createFromArray($tokens);

        $this->assertCount(7, $tokens->all());
        $this->assertSame(Words::Pronoun, $tokens->current()->name);
        $this->assertSame('I', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertSame(Words::Space, $tokens->current()->name);
        $this->assertSame(' ', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertSame(Words::Verb, $tokens->current()->name);
        $this->assertSame('love', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertSame(Words::Space, $tokens->current()->name);
        $this->assertSame(' ', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertSame(Words::Pronoun, $tokens->current()->name);
        $this->assertSame('my', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertSame(Words::Space, $tokens->current()->name);
        $this->assertSame(' ', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertSame(Words::Noun, $tokens->current()->name);
        $this->assertSame('brick', $tokens->current()->value);
        $tokens = $tokens->navigate(new Next());
        $this->assertNull($tokens);
    }
}
