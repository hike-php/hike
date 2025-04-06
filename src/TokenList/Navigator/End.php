<?php

declare(strict_types=1);

namespace Hike\TokenList\Navigator;

use Hike\TokenList\Navigator;
use Hike\TokenList\Tokens;

final readonly class End implements Navigator
{
    public function apply(Tokens $tokens): Tokens
    {
        return Tokens::createFromArray($tokens->all(), \count($tokens->all()) - 1);
    }
}
