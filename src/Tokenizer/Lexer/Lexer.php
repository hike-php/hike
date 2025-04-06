<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Lexer;

use Hike\Tokenizer\Token;
use Hike\TokenList\Tokens;

interface Lexer
{
    public function match(Tokens $tokens): ?Token;
}
