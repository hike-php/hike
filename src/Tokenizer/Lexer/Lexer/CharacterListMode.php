<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Lexer\Lexer;

enum CharacterListMode
{
    case Inclusive;
    case Exclusive;
}
