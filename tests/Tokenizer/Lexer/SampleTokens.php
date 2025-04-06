<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Lexer;

/**
 * Token names for use in tests since PHP doesn't support anonymous enums
 */
enum SampleTokens
{
    case Token1;
    case Token2;
    case Token3;
    case Token4;
}
