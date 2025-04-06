<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Output;

use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Debug;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DebugTest extends TestCase
{
    #[Test]
    public function it_serializes_with_debug_marker(): void
    {
        $tokens = Tokens::create(new Tokenizer('That money was just resting in my account'));

        $tokens = $tokens->navigate(new Next('resting'));

        $output = $tokens?->output(new Debug());

        $this->assertSame("That money was just \e[31m[->]\e[0mresting in my account", $output);
    }
}
