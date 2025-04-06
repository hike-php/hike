<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Character;

use Hike\Tokenizer\Character\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    #[Test]
    public function it_tokenizes_a_string_into_characters(): void
    {
        $originalString = 'Careful now';

        $tokens = Tokens::create(new Tokenizer($originalString));

        $this->assertSame('C', $tokens->current()?->value);

        $tokens = $tokens->navigate(new Next());

        $this->assertSame('a', $tokens?->current()?->value);

        $this->assertSame($originalString, $tokens->output(new Serialize()));

        $this->assertSame(\strlen($originalString), \count($tokens->all()));
    }
}
