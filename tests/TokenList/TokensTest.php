<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList;

use Hike\Tokenizer\Token;
use Hike\Tokenizer\Tokenizer;
use Hike\Tokenizer\Word\Token as WordToken;
use Hike\Tokenizer\Word\Tokenizer as WordTokenizer;
use Hike\TokenList\Modifier;
use Hike\TokenList\Navigator;
use Hike\TokenList\Navigator\Start;
use Hike\TokenList\Output;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TokensTest extends TestCase
{
    #[Test]
    public function it_loads_tokens_from_a_tokenizer(): void
    {
        $tokenizer = $this->createMock(Tokenizer::class);
        $token = $this->createMock(Token::class);

        $tokenizer
            ->expects($this->once())
            ->method('getTokens')
            ->willReturn([
                $this->createMock(Token::class),
                $this->createMock(Token::class),
                $this->createMock(Token::class),
            ])
        ;

        $tokens = Tokens::create($tokenizer);
        $this->assertCount(3, $tokens->all());
        $this->assertEquals(0, $tokens->cursorPosition);
    }

    #[Test]
    public function it_can_move_between_tokens(): void
    {
        $tokenizer = $this->createMock(Tokenizer::class);
        $token = $this->createMock(Token::class);

        $tokenizer
            ->expects($this->once())
            ->method('getTokens')
            ->willReturn([
                $this->createMock(Token::class),
                $this->createMock(Token::class),
                $this->createMock(Token::class),
                $this->createMock(Token::class),
                $this->createMock(Token::class),
            ])
        ;

        $tokens = Tokens::create($tokenizer);

        $this->assertSame(0, $tokens->cursorPosition);
        $tokens = $tokens->move(1);
        $this->assertSame(1, $tokens?->cursorPosition);
        $tokens = $tokens->move(3);
        $this->assertSame(4, $tokens?->cursorPosition);
        $tokens = $tokens->move(-2);
        $this->assertSame(2, $tokens?->cursorPosition);
    }

    #[Test]
    public function it_returns_the_current_token(): void
    {
        $tokens = Tokens::create(new WordTokenizer('Don\'t buy priest socks in a normal shop'));

        $this->assertSame(0, $tokens->cursorPosition);
        $this->assertSame(WordToken::Word, $tokens->current()?->name);
        $this->assertSame('Don\'t', $tokens->current()->value);

        $tokens = $tokens->move(1);
        $this->assertSame(1, $tokens?->cursorPosition);
        $this->assertSame(WordToken::Space, $tokens->current()?->name);

        $tokens = $tokens->move(3);
        $this->assertSame(4, $tokens?->cursorPosition);
        $this->assertSame(WordToken::Word, $tokens->current()?->name);
        $this->assertSame('priest', $tokens->current()->value);

        $tokens = $tokens->move(-2);
        $this->assertSame(2, $tokens?->cursorPosition);
        $this->assertSame(WordToken::Word, $tokens->current()?->name);
        $this->assertSame('buy', $tokens->current()->value);
    }

    #[Test]
    public function it_applies_a_navigator(): void
    {
        $tokens = Tokens::create(new WordTokenizer('Go on, go on, go on'));

        $tokens = $tokens->navigate(
            new class () implements Navigator {
                public function apply(Tokens $tokens): ?Tokens
                {
                    return $tokens->move(4);
                }
            },
        );

        $this->assertSame('go', $tokens?->current()?->value);
    }

    #[Test]
    public function it_applies_chained_navigators(): void
    {
        $tokens = Tokens::create(new WordTokenizer('as I said last time, it won\'t happen again'));

        $tokens = $tokens->navigate(
            new class () implements Navigator {
                public function apply(Tokens $tokens): ?Tokens
                {
                    return $tokens->move(1);
                }
            },
            new class () implements Navigator {
                public function apply(Tokens $tokens): ?Tokens
                {
                    return $tokens->move(1);
                }
            },
            new class () implements Navigator {
                public function apply(Tokens $tokens): ?Tokens
                {
                    return $tokens->move(1);
                }
            },
            new class () implements Navigator {
                public function apply(Tokens $tokens): ?Tokens
                {
                    return $tokens->move(1);
                }
            },
        );

        $this->assertSame('said', $tokens?->current()?->value);
    }

    #[Test]
    public function it_applies_a_modifier(): void
    {
        $tokens = Tokens::create(new WordTokenizer('Chewing gum for the eyes!'));

        $tokens = $tokens->modify(
            new class () implements Modifier {
                public function apply(Tokens $tokens): Tokens
                {
                    return Tokens::createFromArray(\array_reverse($tokens->all()));
                }
            },
        );

        $tokens = $tokens->navigate(new Start());

        $this->assertSame('eyes!', $tokens?->current()?->value);
    }

    #[Test]
    public function it_applies_chained_modifiers(): void
    {
        $tokens = Tokens::create(new WordTokenizer('I\'m a happy camper!'));

        $this->assertCount(7, $tokens->all());

        $tokens = $tokens->modify(
            new class () implements Modifier {
                public function apply(Tokens $tokens): Tokens
                {
                    $tokens = $tokens->all();
                    \array_pop($tokens);

                    return Tokens::createFromArray($tokens);
                }
            },
            new class () implements Modifier {
                public function apply(Tokens $tokens): Tokens
                {
                    $tokens = $tokens->all();
                    \array_pop($tokens);

                    return Tokens::createFromArray($tokens);
                }
            },
        );

        $this->assertCount(5, $tokens->all());
        $this->assertSame('I\'m a happy', $tokens->output(new Serialize()));
    }

    #[Test]
    public function it_applies_an_outputter(): void
    {
        $tokens = Tokens::create(new WordTokenizer('Are those my feet?'));

        $output = $tokens->output(
            new class () implements Output {
                public function apply(Tokens $tokens): string
                {
                    return (string) \count($tokens->all());
                }
            },
        );

        $this->assertSame('7', $output);
    }
}
