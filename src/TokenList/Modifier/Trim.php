<?php

declare(strict_types=1);

namespace Hike\TokenList\Modifier;

use Hike\TokenList\Modifier;
use Hike\TokenList\Tokens;

final readonly class Trim implements Modifier
{
    public function __construct(
        private \UnitEnum|string $type,
    ) {}

    public function apply(Tokens $tokens): Tokens
    {
        return $tokens->modify(
            new Ltrim($this->type),
            new Rtrim($this->type),
        );
    }
}
