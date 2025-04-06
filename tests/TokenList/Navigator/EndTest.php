<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Navigator;

use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Navigator\End;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EndTest extends TestCase
{
    #[Test]
    public function it_navigates_back_to_the_start(): void
    {
        $tokens = Tokens::create(new Tokenizer('We built an extension on the extension and now the house is a circle'));

        $this->assertSame('We', $tokens->current()->value);

        $tokens = $tokens->navigate(new End());

        $this->assertSame('circle', $tokens?->current()->value);
    }
}
