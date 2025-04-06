<?php

declare(strict_types=1);

namespace Hike\Tokenizer;

interface Tokenizer
{
    /**
     * @return Token[]
     */
    public function getTokens(): array;
}
