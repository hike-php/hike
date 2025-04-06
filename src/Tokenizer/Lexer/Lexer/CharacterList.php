<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Lexer\Lexer;

use Hike\Tokenizer\Lexer\Lexer;
use Hike\Tokenizer\Token as TokenInterface;
use Hike\Tokenizer\Token\Token;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;

final readonly class CharacterList implements Lexer
{
    /**
     * @param string[] $allowedCharacters
     */
    public function __construct(
        private \UnitEnum $tokenName,
        private array $allowedCharacters,
        private CharacterListMode $mode = CharacterListMode::Inclusive,
    ) {}

    public function match(Tokens $tokens): ?TokenInterface
    {
        if ($tokens->current() === null) {
            return null;
        }

        $matchMode = $this->mode === CharacterListMode::Inclusive;
        $chars = [];

        do {
            if (\in_array($tokens->current()?->value, $this->allowedCharacters, true) !== $matchMode) {
                break;
            }
            $chars[] = $tokens->current()?->value;
        } while ($tokens = $tokens->navigate(new Next()));

        if (empty($chars)) {
            return null;
        }

        return new Token($this->tokenName, \implode('', $chars));
    }
}
