<?php

declare(strict_types=1);

namespace Hike\TokenList;

interface Navigator
{
    public function apply(Tokens $tokens): ?Tokens;
}
