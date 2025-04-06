<?php

declare(strict_types=1);

namespace Hike\TokenList\Navigator;

use Hike\TokenList\Navigator;
use Hike\TokenList\Tokens;

final class MatchingClose implements Navigator
{
    /** @var array<string, string> */
    private array $matchingBrackets = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    public function apply(Tokens $tokens): ?Tokens
    {
        $open = $tokens->current()->value;
        $close = $this->matchingBrackets[$open] ?? null;

        if ($close === null) {
            throw new \RuntimeException(\sprintf('Cannot find matching close bracket for "%s"', $open));
        }

        $depth = 0;

        while ($tokens = $tokens->navigate(new Next())) {
            if ($tokens->current()->value === $close && $depth === 0) {
                break;
            }
            if ($tokens->current()->value === $open) {
                $depth++;
            } elseif ($tokens->current()->value === $close) {
                $depth--;
            }
        }

        return $tokens;
    }
}
