# Hike - Tokenize, navigate around and modify any text format

A consistent interface to analyse and modify any text format. Programming languages, natural languages or any text format.

There are inbuilt (and extensible) navigation tools for moving between tokens and editing/inserting text into the tokeninzed object.


Includes a very minimalistic reference implementation for words/spaces which is shown below.

This is for demonstration purposes as Hike is intended as a foundation for building customised tokenizers with their own lexers.

## 1. Navigating

```php

use Hike\Tokenizer\Word\Tokenizer;
use Hike\Tokenizer\Word\Token;
use Hike\TokenList\Tokens;
use Hike\TokenList\Navigator\Next;

$string = 'This is a string containing some words and spaces';

$tokens = Tokens::create(new Tokenizer($string));

echo $tokens->current()->value; // prints "This"

// Move to next word
$tokens = $tokens->navigate(new Next(Token::Word));

echo $tokens->current()->value; // prints "is"

// Move to next occurrenc of "some"
$tokens = $tokens->navigate(new Next('some'));

// Move to the next occurrent of either "words" or "spaces", whichever is first
$tokens = $tokens->navigate(
    new Next('words', 'spaces')
);

```

## 2. Modifying/outputting

```php
use Hike\Tokenizer\Word\Tokenizer;
use Hike\Tokenizer\Word\Token;
use Hike\TokenList\Tokens;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Output\Serialize;
use Hike\TokenList\Modifier\Remove;

$string = 'This is a string containing some words and spaces';

$tokens = Tokens::create(new Tokenizer($string));

// Move to 'containing'
// remove the token
// Print the resulting tokens
// Prints 'This is a string some words and spaces'
echo $tokens = $tokens->navigate(new Next('containing'))
    ->modify(new Remove())
    ->output(new Serialize())
;

```

## 3. Selection


```php

use Hike\Tokenizer\Word\Tokenizer;
use Hike\Tokenizer\Word\Token;
use Hike\TokenList\Tokens;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Selection;

$string = 'This is a string containing some words and spaces';

$tokens = Tokens::create(new Tokenizer($string));

$tokens = $tokens->navigate(new Next('string'));

// Select everything from the current token to the next occurrence of 'words'
// and copy it
$selection = Selection::from($tokens)
    ->to(new Next('words'))
    ->copy()
;

// Prints 'string containing some words'
echo $selection->output(new Serialize());


// Select everything from the current token to the next occurrence of 'words'
// and delete it from the original token list
$selection = Selection::from($tokens)
    ->to(new Next('words'))
    ->delete()
;

// Prints 'This is a  and spaces'
echo $selection->output(new Serialize());
```


## 4. Custom tokenizer with configured lexers

Hike contains a `Words` tokenizer by default, below is an example implementing a more complex implementation that supports the following tokens:

1. Words
2. Spaces
3. Numbers
4. Punctuation

Firstly, define an enum of the possible tokens:

```php
namespace My\Tokenizer;

enum Token
{
    case Word;
    case Space;
    case Number;
    case Punctuation;
}
```


Secondly define a tokenizer. There is an existing `CharacterList` lexer which matches one or more consecutive chracters


```php

namespace My\Tokenizer;

use Hike\Tokenizer\Tokenizer as HikeTokenizer;
use Hike\Tokenizer\Lexer\Lexer\CharacterList;
use Hike\Tokenizer\Lexer\Tokenizer as LexerTokenizer;

final readonly class Tokenizer implements HikeTokenizer
{
    public function __construct(
        private string $string,
    ) {}

    public function getTokens(): array
    {
        // use inbuilt LexerTokenizer which iterates over the string and extracts tokens using lexers
        // n.b. LezerTokenizer is a Hike tokenizer implementation which tokenizes a string into characters
        $tokenizer = LexerTokenizer::fromString($this->string);

        $tokenizer = $tokenizer
            // If a continuous string of characters A-z are matched it will be represented by the token Word
            ->addLexer(new CharacterList(Token::Word, range('A', 'z'))
            // One or more spaces will be Token::Space
            ->addLexer(new CharacterList(Token::Space, [ ' ', "\t", "\n", "\r" ])
            // And additional character sets can be mapeed to other tokens
            ->addLexer(new CharacterList(Token::Number, range(0, 9))
            ->addLexer(new CharacterList(Token::Punctuation, ['.', ',', ':', '?', ';'])
        ;

        return $tokenizer->getTokens();
    }
}
```

Other Lexers can match Keywords or other substrings of characters.

Finally, use your configured tokenizer:


```php
use My\Tokenizer\Tokenizer;
use My\Tokenizer\Token;
use Hike\TokenList\Tokens;
use Hike\TokenList\Navigator\Next;
use Hike\TokenList\Selection;


$string = 'Is it working? Who knows?';

$tokens = Tokens::create(new Tokenizer($string));

// Move to the first punctuation character
$tokens->navigate(new Next(Token::Punctuation));

// Returns Token::Punctuation
$type = $tokens->current()->name;

// Prints '?'
echo $tokens->current()->value;


$tokens = $tokens->navigate(new Next());

// Returns Token::Space
$type = $tokens->current()->name;

// Prints ' '
echo $tokens->current()->value;
```
