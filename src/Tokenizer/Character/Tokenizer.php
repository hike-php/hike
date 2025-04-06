<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Character;

use Hike\Tokenizer\Token\Token;
use Hike\Tokenizer\Tokenizer as TokenizerInterface;

/**
 * @description Loads an arbitrary string into a series of single character tokens
 *              designed to be easier to maniuplate than pure php strings for
 *              other tokenizers
 */
final readonly class Tokenizer implements TokenizerInterface
{
    public function __construct(
        private readonly string $string,
    ) {}

    public function getTokens(): array
    {
        $tokens = [];
        for ($i = 0; $i < \strlen($this->string); $i++) {
            $tokens[] = new Token(Tokens::Character, $this->string[$i]);
        }
        return $tokens;
    }
}
