<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Lexer;

use Hike\Tokenizer\Lexer\Lexer;
use Hike\Tokenizer\Lexer\Tokenizer;
use Hike\Tokenizer\Token as TokenInterface;
use Hike\Tokenizer\Token\Token;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    #[Test]
    public function it_tokenizes_using_defined_lexers(): void
    {
        $lexer1 = new class () implements Lexer {
            public function match(Tokens $tokens): ?TokenInterface
            {
                if ($tokens->current()->value === '1') {
                    return new Token(SampleTokens::Token1, '1');
                }
                return null;
            }
        };

        $lexer2 = new class () implements Lexer {
            public function match(Tokens $tokens): ?TokenInterface
            {
                if ($tokens->current()->value === '2') {
                    return new Token(SampleTokens::Token2, '2');
                }
                return null;
            }
        };

        $lexer3 = new class () implements Lexer {
            public function match(Tokens $tokens): ?TokenInterface
            {
                if ($tokens->current()->value === '3') {
                    return new Token(SampleTokens::Token3, '3');
                }
                return null;
            }
        };

        $tokenizer = Tokenizer::fromString('12311')
            ->addLexer($lexer1, $lexer2, $lexer3)
        ;

        $tokens = $tokenizer->getTokens();

        $this->assertCount(5, $tokens);
        $this->assertInstanceOf(Token::class, $tokens[0]);
        $this->assertSame(SampleTokens::Token1, $tokens[0]->name);
        $this->assertSame('1', $tokens[0]->value);
        $this->assertInstanceOf(Token::class, $tokens[1]);
        $this->assertSame(SampleTokens::Token2, $tokens[1]->name);
        $this->assertSame('2', $tokens[1]->value);
        $this->assertInstanceOf(Token::class, $tokens[2]);
        $this->assertSame(SampleTokens::Token3, $tokens[2]->name);
        $this->assertSame('3', $tokens[2]->value);
        $this->assertInstanceOf(Token::class, $tokens[3]);
        $this->assertSame(SampleTokens::Token1, $tokens[3]->name);
        $this->assertSame('1', $tokens[3]->value);
        $this->assertInstanceOf(Token::class, $tokens[4]);
        $this->assertSame(SampleTokens::Token1, $tokens[4]->name);
        $this->assertSame('1', $tokens[4]->value);
    }

    #[Test]
    public function it_supports_multiple_character_tokens(): void
    {
        // Match single space
        $lexer1 = new class () implements Lexer {
            public function match(Tokens $tokens): ?TokenInterface
            {
                if ($tokens->current()->value === ' ') {
                    return new Token(SampleTokens::Token1, ' ');
                }
                return null;
            }
        };

        // Match any number of non space characters
        $lexer2 = new class () implements Lexer {
            public function match(Tokens $tokens): ?TokenInterface
            {
                if ($tokens->current()->value === ' ') {
                    return null;
                }

                $string = '';
                do {
                    $string .= $tokens->current()->value;
                    $tokens = $tokens->move(1);
                } while ($tokens && $tokens->current()->value !== ' ');


                return new Token(SampleTokens::Token2, $string);
            }
        };

        $tokenizer = Tokenizer::fromString('That would be an ecumenical matter')
            ->addLexer($lexer1, $lexer2)
        ;

        $tokens = $tokenizer->getTokens();

        $this->assertCount(11, $tokens);
        $this->assertSame(SampleTokens::Token2, $tokens[0]->name);
        $this->assertSame('That', $tokens[0]->value);
        $this->assertSame(SampleTokens::Token1, $tokens[1]->name);
        $this->assertSame(' ', $tokens[1]->value);
        $this->assertSame(SampleTokens::Token2, $tokens[2]->name);
        $this->assertSame('would', $tokens[2]->value);
        $this->assertSame(SampleTokens::Token2, \end($tokens)->name);
        $this->assertSame('matter', \end($tokens)->value);

    }
}
