<?php

declare(strict_types=1);

namespace Tests\Hike\Tokenizer\Lexer\Lexer;

enum Words
{
    case Noun;
    case Verb;
    case Pronoun;
    case Space;
}
