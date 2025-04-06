<?php

declare(strict_types=1);

namespace Hike\TokenList;

interface Modifier
{
    public function apply(Tokens $tokens): Tokens;
}
