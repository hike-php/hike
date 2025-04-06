<?php

declare(strict_types=1);

namespace Tests\Hike\TokenList\Selection;

use Hike\Tokenizer\Character\Tokenizer;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Selection;
use Hike\TokenList\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SelectionTest extends TestCase
{
    #[Test]
    public function it_can_copy_a_selection(): void
    {
        $tokens = Tokens::create(new Tokenizer('A string with a part that should be selected and some trailing text.'))
            ->navigate(new Next('a'))
        ;

        $this->assertNotNull($tokens);
        $selection = Selection::from($tokens)
            ->to(new Next('d'), new Next('d'))
        ;

        $result = $selection->copy();

        $this->assertSame('a part that should be selected', $result->output(new Serialize()));
    }

    #[Test]
    public function it_can_copy_a_selection_by_number_of_tokens(): void
    {
        $tokens = Tokens::create(new Tokenizer('A string with a part that should be selected and some trailing text.'))
            ->navigate(new Next('a'))
        ;

        $this->assertNotNull($tokens);
        $selection = Selection::from($tokens)
            ->toNthToken(5)
        ;

        $result = $selection->copy();

        $this->assertSame('a part', $result->output(new Serialize()));
    }

    #[Test]
    public function it_can_delete_a_selection(): void
    {
        $tokens = Tokens::create(new Tokenizer('A string with a part that should be deleted and some trailing text.'))
            ->navigate(new Next('a'))
        ;

        $this->assertNotNull($tokens);
        $selection = Selection::from($tokens)
            ->to(
                new Next('d'), //d in should
                new Next('d'), //d start deleted
                new Next('d'), //d end deleted
                new Next(' '), //trailing space
            )
        ;

        $result = $selection->delete();

        $this->assertSame('A string with and some trailing text.', $result->output(new Serialize()));
    }

    #[Test]
    public function it_can_delete_a_selection_by_number_of_tokens(): void
    {
        $tokens = Tokens::create(new Tokenizer('A string with a part that should be selected and some trailing text.'))
            ->navigate(new Next('a'))
        ;

        $this->assertNotNull($tokens);
        $selection = Selection::from($tokens)
            ->toNthToken(6)
        ;

        $result = $selection->delete();

        $this->assertSame('A string with that should be selected and some trailing text.', $result->output(new Serialize()));
    }
}
