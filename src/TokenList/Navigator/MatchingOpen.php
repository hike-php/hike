<?php

declare(strict_types=1);

namespace Hike\TokenList\Navigator;

use Hike\TokenList\Navigator;
use Hike\TokenList\Tokens;

final class MatchingOpen implements Navigator
{
    /** @var array<string, string> */
    private array $matchingBrackets = [
        ')' => '(',
        ']' => '[',
        '}' => '{',
    ];

    public function apply(Tokens $tokens): ?Tokens
    {
        $close = $tokens->current()?->value;
        $open = $this->matchingBrackets[$close] ?? null;

        if ($open === null) {
            throw new \RuntimeException(\sprintf('Cannot find matching open bracket for "%s"', $close));
        }

        $depth = 0;

        while ($tokens = $tokens->navigate(new Previous())) {
            if ($tokens->current()?->value === $open && $depth === 0) {
                break;
            }
            if ($tokens->current()?->value === $close) {
                $depth++;
            } elseif ($tokens->current()?->value === $open) {
                $depth--;
            }
        }

        return $tokens;
    }
}
