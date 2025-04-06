<?php

declare(strict_types=1);

namespace Hike\TokenList\Modifier;

use Hike\TokenList\Modifier;
use Hike\TokenList\Navigator\End;
use Hike\TokenList\Tokens;

final readonly class Rtrim implements Modifier
{
    public function __construct(
        private \UnitEnum|string $type,
    ) {}

    public function apply(Tokens $tokens): Tokens
    {
        $tokens = $tokens->navigate(new End()) ?? throw new \RuntimeException('Could not move cursor to end');

        while ($tokens->current()->is($this->type)) {
            $tokens = $tokens->modify(new Remove());
        }

        return $tokens;
    }
}
