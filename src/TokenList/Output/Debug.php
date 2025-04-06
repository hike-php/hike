<?php

declare(strict_types=1);

namespace Hike\TokenList\Output;

use Hike\Tokenizer\Token\Token;
use Hike\TokenList\Modifier\Insert;
use Hike\TokenList\Output;
use Hike\TokenList\Tokens;

final readonly class Debug implements Output
{
    public function __construct(
        private string $debugIcon = "\e[31m[->]\e[0m",
    ) {}

    public function apply(Tokens $tokens): string
    {
        $tokens = $tokens
            ->modify(new Insert(Tokens::createFromArray([new Token(DebugToken::DebugIcon, $this->debugIcon)])))
        ;

        return $tokens->output(new Serialize());
    }
}
