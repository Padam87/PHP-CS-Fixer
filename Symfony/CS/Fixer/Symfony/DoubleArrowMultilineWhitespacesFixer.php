<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\Symfony;

use Symfony\CS\AbstractFixer;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * @author Carlos Cirello <carlos.cirello.nl@gmail.com>
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * @author Graham Campbell <graham@mineuk.com>
 */
final class DoubleArrowMultilineWhitespacesFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_DOUBLE_ARROW);
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(T_DOUBLE_ARROW)) {
                continue;
            }

            $this->fixWhitespace($tokens[$index - 1]);
            // do not move anything about if there is a comment following the whitespace
            if (!$tokens[$index + 2]->isComment()) {
                $this->fixWhitespace($tokens[$index + 1]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Operator => should not be arounded by multi-line whitespaces.';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        // should be run before the MultilineArrayTrailingCommaFixer and AlignDoubleArrowFixer
        return 1;
    }

    private function fixWhitespace(Token $token)
    {
        if ($token->isWhitespace() && !$token->isWhitespace(" \t")) {
            $token->setContent(rtrim($token->getContent()).' ');
        }
    }
}
