<?php

declare(strict_types=1);

namespace Hike\TokenList;

use Hike\TokenList\Modifier\Remove;
use Hike\TokenList\Navigator\Next;

final readonly class Selection
{
    private function __construct(
        private Tokens $from,
        private Tokens $to,
    ) {}

    public static function from(Tokens $tokens): self
    {
        return new self($tokens, $tokens);
    }

    public function to(Navigator ...$navigators): self
    {
        $to = $this->from->navigate(...$navigators);

        if ($to === null) {
            throw new \RuntimeException('Invalid to selection');
        }

        return new self($this->from, $to);
    }

    public function toNthToken(int $num): self
    {
        $to = $this->from->move($num);

        if ($to === null) {
            throw new \RuntimeException('Invalid to selection');
        }

        return new self($this->from, $to);
    }

    public function copy(): Tokens
    {
        $selectedTokens = [];

        $tokens = $this->from;
        for ($i = $this->from->cursorPosition; $i <= $this->to->cursorPosition; $i++) {
            if ($tokens?->current() === null) {
                throw new \RuntimeException('Invalid to position');
            }
            $selectedTokens[] = $tokens->current();
            $tokens = $tokens->move(1);
        }

        return Tokens::createFromArray($selectedTokens);
    }

    public function delete(): Tokens
    {
        $fromPosition = $this->from->cursorPosition;
        $toPosition = $this->to->cursorPosition;

        $selectedTokens = [];

        $tokens = $this->from;
        for ($i = $fromPosition; $i <= $toPosition; $i++) {
            $tokens = $tokens->modify(new Remove());

            if ($tokens->navigate(new Next())) {
                $tokens = $tokens->navigate(new Next());
            }
        }

        return $tokens;
    }
}
