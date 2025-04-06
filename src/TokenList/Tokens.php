<?php

declare(strict_types=1);

namespace Hike\TokenList;

use Hike\Tokenizer\Token;
use Hike\Tokenizer\Tokenizer;

/**
 * Immutable list of tokens from a tokenizer.
 * Can be navigated around and modified
 */
final readonly class Tokens
{
    /**
     * @param list<Token> $tokens
     */
    private function __construct(
        private array $tokens = [],
        public int $cursorPosition = 0,
    ) {}

    public static function create(Tokenizer $tokenizer): self
    {
        return new self($tokenizer->getTokens());
    }

    /**
     * @param list<Token> $tokens
     */
    public static function createFromArray(array $tokens, int $cursorPosition = 0): self
    {
        return new self($tokens, $cursorPosition);
    }

    public function current(): ?Token
    {
        return $this->tokens[$this->cursorPosition] ?? null;
    }

    /**
     * @return array<Token>
     */
    public function all(): array
    {
        return $this->tokens;
    }

    public function move(int $i): ?self
    {
        if (!isset($this->tokens[$this->cursorPosition + $i])) {
            return null;
        }

        return new self($this->tokens, $this->cursorPosition + $i);
    }

    public function navigate(Navigator ...$navigators): ?self
    {
        $tokens = $this;
        foreach ($navigators as $navigator) {
            $tokens = $navigator->apply($tokens);
            if ($tokens === null) {
                break;
            }
        }

        return $tokens;
    }

    public function modify(Modifier ...$modifiers): self
    {
        $tokens = $this;
        foreach ($modifiers as $modifier) {
            $tokens = $modifier->apply($tokens);
        }

        return $tokens;
    }

    public function output(Output $output): string
    {
        return $output->apply($this);
    }
}
