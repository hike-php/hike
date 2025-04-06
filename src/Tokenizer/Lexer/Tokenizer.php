<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Lexer;

use Hike\Tokenizer\Character\Tokenizer as CharacterTokenizer;
use Hike\Tokenizer\Token;
use Hike\Tokenizer\Tokenizer as TokenizerInterface;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Tokens;

final readonly class Tokenizer implements TokenizerInterface
{
    /**
     * @param Lexer[] $lexers
     */
    private function __construct(
        private string $string,
        private array $lexers = [],
    ) {}

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public function addLexer(Lexer ...$newLexers): self
    {
        $lexers = $this->lexers;
        foreach ($newLexers as $lexer) {
            $lexers[] = $lexer;
        }
        return new self($this->string, $lexers);
    }

    /**
     * @return Token[]
     */
    public function getTokens(): array
    {
        $resultTokens = [];

        $tokens = Tokens::create(new CharacterTokenizer($this->string));

        while ($tokens !== null) {
            foreach ($this->lexers as $lexer) {
                $token = $lexer->match($tokens);
                if ($token !== null) {
                    $resultTokens[] = $token;

                    $tokens = $tokens->move(\strlen($token->value) - 1);
                    break;
                }
            }

            $tokens = $tokens?->navigate(new Next());
        }

        return $resultTokens;
    }
}
