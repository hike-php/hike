<?php

declare(strict_types=1);

namespace Hike\Tokenizer;

interface Token
{
    // Fix formatting once php-cs-fixer allows { get; } in 8.4 support
    public \UnitEnum $name {
        get;
    }

    public string $value {
        get;
    }

    public function is(string|\UnitEnum ...$types): bool;
}
