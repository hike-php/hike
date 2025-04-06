<?php

declare(strict_types=1);

namespace Hike\TokenList\Output;

use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Navigator\Start;
use Hike\TokenList\Output;
use Hike\TokenList\Tokens;

final readonly class Serialize implements Output
{
    public function apply(Tokens $parser): string
    {
        $parser = $parser->navigate(new Start()) ?? throw new \RuntimeException('Tokens are empty');

        $out = '';
        do {
            $out .= $parser->current()->value;
        } while ($parser = $parser->navigate(new Next()));

        return $out;
    }
}
