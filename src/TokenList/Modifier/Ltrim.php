<?php

declare(strict_types=1);

namespace Hike\TokenList\Modifier;

use Hike\TokenList\Modifier;
use Hike\TokenList\Navigator\Start;
use Hike\TokenList\Tokens;

final readonly class Ltrim implements Modifier
{
    public function __construct(
        private string|\UnitEnum $type,
    ) {}

    public function apply(Tokens $tokens): Tokens
    {
        $tokens = $tokens->navigate(new Start()) ?? throw new \RuntimeException('Could not move cursor to start');

        while ($tokens->current()->is($this->type)) {
            $tokens = $tokens->modify(new Remove());
        }

        return $tokens;
    }
}
