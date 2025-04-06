<?php

declare(strict_types=1);

namespace Hike\TokenList\Modifier;

use Hike\Tokenizer\Token;
use Hike\TokenList\Modifier;
use Hike\TokenList\Tokens;

final readonly class Insert implements Modifier
{
    public function __construct(
        private Tokens $newTokens,
        private InsertMode $mode = InsertMode::Insert,
    ) {}

    public function apply(Tokens $tokens): Tokens
    {
        $rawTokens = $tokens->all();

        if (\count($rawTokens) === 0) {
            return $this->newTokens;
        }

        $relativePosition = $this->mode === InsertMode::Append ? 1 : 0;
        \array_splice($rawTokens, $tokens->cursorPosition + $relativePosition, 0, $this->newTokens->all());

        /** @var list<Token> $rawTokens */
        return Tokens::createFromArray($rawTokens, $tokens->cursorPosition + \count($this->newTokens->all()));
    }
}
