<?php

declare(strict_types=1);

namespace Hike\TokenList\Navigator;

use Hike\TokenList\Navigator;
use Hike\TokenList\Tokens;

final readonly class SkipOver implements Navigator
{
    /** @var array<\UnitEnum|string> */
    private readonly array $searches;

    public function __construct(\UnitEnum|string ...$searches)
    {
        $this->searches = $searches;
    }

    public function apply(Tokens $tokens): ?Tokens
    {
        while ($tokens && $tokens->current()?->is(...$this->searches)) {
            $tokens = $tokens->navigate(new Next());
        }

        return $tokens;
    }
}
