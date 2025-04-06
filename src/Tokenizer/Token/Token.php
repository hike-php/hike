<?php

declare(strict_types=1);

namespace Hike\Tokenizer\Token;

use Hike\Tokenizer\Token as TokenInterface;

final readonly class Token implements TokenInterface
{
    public function __construct(
        public \UnitEnum $name,
        public string $value,
    ) {}

    public function is(\UnitEnum|string ...$types): bool
    {
        foreach ($types as $type) {
            if ($type instanceof \UnitEnum && $this->name === $type) {
                return true;
            }

            if ($type === $this->value) {
                return true;
            }
        }

        return false;
    }
}
