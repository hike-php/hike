<?php

declare(strict_types=1);

namespace Hike\TokenList\Navigator;

use Hike\TokenList\Navigator;
use Hike\TokenList\Tokens;

final readonly class Next implements Navigator
{
    /** @var array<\UnitEnum|string> */
    private readonly array $searches;

    public function __construct(\UnitEnum|string ...$searches)
    {
        $this->searches = $searches;
    }

    public function apply(Tokens $tokens): ?Tokens
    {
        $tokens = $tokens->move(1);

        if ($this->searches === []) {
            return $tokens;
        }

        while ($tokens && !$tokens->current()?->is(...$this->searches)) {
            $tokens = $tokens->move(1);
        }

        return $tokens;
    }
}
