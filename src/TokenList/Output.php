<?php

declare(strict_types=1);

namespace Hike\TokenList;

interface Output
{
    public function apply(Tokens $tokens): string;
}
