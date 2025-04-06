<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Modifier;

use Hike\Tokenizer\Word\Tokenizer;
use Hike\TokenList\Modifier\Remove;
use Hike\TokenList\Navigator\End;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RemoveTest extends TestCase
{
    #[Test]
    public function it_removes_the_current_token(): void
    {
        $tokens = Tokens::create(new Tokenizer('Rabbits, tennis, you know, that whole connection there'));

        $tokens = $tokens->modify(new Remove());

        $output = $tokens->output(new Serialize());

        $this->assertSame(' tennis, you know, that whole connection there', $output);

        $tokens = $tokens->navigate(new End());

        $tokens = $tokens?->modify(new Remove());

        $output = $tokens?->output(new Serialize());

        $this->assertSame(' tennis, you know, that whole connection ', $output);
    }
}
