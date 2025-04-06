<?php

declare(strict_types=1);

namespace Hike\TokenList\Navigator;

use Hike\TokenList\Navigator;
use Hike\TokenList\Tokens;

final readonly class Start implements Navigator
{
    public function apply(Tokens $tokens): ?Tokens
    {
        if (empty($tokens->all())) {
            return null;
        }

        return Tokens::createFromArray($tokens->all(), 0);
    }
}
