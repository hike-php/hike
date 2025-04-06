<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Output;

use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Insert;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SerializeTest extends TestCase
{
    #[Test]
    public function it_serializes_the_tokens(): void
    {
        $input = 'That money was just resting in my account';
        $tokens = Tokens::create(new Tokenizer($input));

        $output = $tokens->output(new Serialize());

        $this->assertSame($input, $output);
    }

    #[Test]
    public function it_serializes_the_tokens_after_modification(): void
    {
        $input = 'That money was just resting in my account';
        $tokens = Tokens::create(new Tokenizer($input));

        $tokens = $tokens->navigate(new Next('resting'), new Next())
            ?->modify(new Insert(Tokens::create(new Tokenizer(' there'))))
        ;

        $this->assertNotNull($tokens);
        $output = $tokens->output(new Serialize());

        $this->assertSame('That money was just resting there in my account', $output);
    }
}
