<?php

declare(strict_types=1);

namespace Hike\TokenList\Modifier;

use Hike\TokenList\Modifier;
use Hike\TokenList\Tokens;

final readonly class Remove implements Modifier
{
    public function apply(Tokens $tokens): Tokens
    {
        $rawTokens = $tokens->all();
        \array_splice($rawTokens, $tokens->cursorPosition, 1);

        $newPosition = $tokens->cursorPosition - 1;

        if ($newPosition < 0) {
            $newPosition = 0;
        }

        return Tokens::createFromArray($rawTokens, $newPosition);
    }
}
