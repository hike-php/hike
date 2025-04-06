<?php

declare(strict_types=1);

namespace Hike\TokenList\Modifier;

use Hike\TokenList\Modifier;
use Hike\TokenList\Tokens;

final readonly class Replace implements Modifier
{
    public function __construct(
        private Tokens $newTokens,
    ) {}

    public function apply(Tokens $tokens): Tokens
    {
        return $tokens->modify(
            new Remove(),
            new Insert($this->newTokens, InsertMode::Append),
        );
    }
}
