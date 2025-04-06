<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Word;

use Hike\Tokenizer\Lexer\Lexer\CharacterList;
use Hike\Tokenizer\Lexer\Lexer\CharacterListMode;
use Hike\Tokenizer\Lexer\Tokenizer as LexerTokenizer;
use Hike\Tokenizer\Tokenizer as TokenizerInterface;

final readonly class Tokenizer implements TokenizerInterface
{
    public function __construct(
        private string $string,
    ) {}

    public function getTokens(): array
    {
        $tokenizer = LexerTokenizer::fromString($this->string);

        $whiteSpaceCharacters = [' ', "\n", "\r", "\t"];

        $tokenizer = $tokenizer
            ->addLexer(new CharacterList(Token::Word, $whiteSpaceCharacters, CharacterListMode::Exclusive))
            ->addLexer(new CharacterList(Token::Space, $whiteSpaceCharacters, CharacterListMode::Inclusive))
        ;

        return $tokenizer->getTokens();
    }
}
