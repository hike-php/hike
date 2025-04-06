<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Navigator;

use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Navigator\Start;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StartTest extends TestCase
{
    #[Test]
    public function it_navigates_back_to_the_start(): void
    {
        $tokens = Tokens::create(new Tokenizer('Don\'t eat jam from the jar!'));

        $tokens = $tokens->navigate(new Next('eat'));

        $this->assertSame('eat', $tokens?->current()?->value);

        $tokens = $tokens->navigate(new Start());

        $this->assertSame('Don\'t', $tokens?->current()?->value);
    }

    #[Test]
    public function it_returns_null_if_tokens_are_empty(): void
    {
        $tokens = Tokens::createFromArray([]);

        $tokens = $tokens->navigate(new Start());

        $this->assertNull($tokens);
    }
}
