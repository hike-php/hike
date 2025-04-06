<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Lexer\Lexer;

use Hike\Tokenizer\Lexer\Lexer;
use Hike\Tokenizer\Token as TokenInterface;
use Hike\Tokenizer\Token\Token;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Selection;
use Hike\TokenList\Tokens;

final readonly class Word implements Lexer
{
    /**
     * @var array<string>
     */
    private array $words;

    public function __construct(
        private \UnitEnum $tokenName,
        string ...$words,
    ) {
        $this->words = $words;
    }

    public function match(Tokens $tokens): ?TokenInterface
    {
        foreach ($this->words as $word) {
            $selection = Selection::from($tokens)->toNthToken(\strlen($word) - 1)->copy()->output(new Serialize());
            if ($selection === $word) {
                return new Token($this->tokenName, $selection);
            }
        }

        return null;
    }
}
