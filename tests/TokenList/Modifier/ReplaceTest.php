<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Modifier;

use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Replace;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReplaceTest extends TestCase
{
    #[Test]
    public function it_replaces_the_current_token_with_a_single_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('Rabbits, tennis, you know, that whole connection there'));

        $tokens = $tokens->navigate(new Next('that'));

        $tokens = $tokens->modify(new Replace(Tokens::create(new Tokenizer('the'))));

        $output = $tokens->output(new Serialize());

        $this->assertSame('Rabbits, tennis, you know, the whole connection there', $output);

    }
}
