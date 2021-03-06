<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Tokenizer\Transformer;

use Symfony\CS\Tokenizer\AbstractTransformer;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * Transform `array` typehint from T_ARRAY into T_ARRAY_TYPEHINT.
 *
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * @internal
 */
final class ArrayTypehintTransformer extends AbstractTransformer
{
    /**
     * {@inheritdoc}
     */
    public function getCustomTokenNames()
    {
        return array('CT_ARRAY_TYPEHINT');
    }

    /**
     * {@inheritdoc}
     */
    public function process(Tokens $tokens, Token $token, $index)
    {
        if (!$token->isGivenKind(T_ARRAY)) {
            return;
        }

        $nextIndex = $tokens->getNextMeaningfulToken($index);
        $nextToken = $tokens[$nextIndex];

        if (!$nextToken->equals('(')) {
            $token->override(array(CT_ARRAY_TYPEHINT, $token->getContent()));
        }
    }
}
