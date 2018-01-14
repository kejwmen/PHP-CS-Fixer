<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Fixer\LanguageConstruct;

use PhpCsFixer\AbstractFunctionReferenceFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @author Mateusz Sip <mateusz.sip@gmail.com
 */
final class JsonDecodeAssocConstantFixer extends AbstractFunctionReferenceFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Replaces json decode call second argument with constant',
            [new CodeSample("<?php\n\$a = json_decode(\$jsonString, true);\n")],
            null,
            null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        for ($index = $tokens->count() - 4; $index > 0; --$index) {
            if (!$tokens[$index]->isGivenKind(T_STRING)) {
                continue;
            }

            if (!$tokens[$index]->getContent('json_decode')) {
                continue;
            }

            $braceOpenIndex = $tokens->getNextMeaningfulToken($index);
            if (!$tokens[$braceOpenIndex]->equals('(')) {
                continue;
            }

            // test if function call without parameters
            $firstArgumentIndex = $tokens->getNextMeaningfulToken($braceOpenIndex);
            $commaIndex = $tokens->getNextMeaningfulToken($firstArgumentIndex);
            $secondArgumentIndex = $tokens->getNextMeaningfulToken($commaIndex);
            $secondArgumentToken = $tokens[$secondArgumentIndex];

            if ($secondArgumentToken->getContent() === 'JSON_OBJECT_AS_ARRAY') {
                continue;
            }

            if (!($secondArgumentToken->getContent() == true)) {
                continue;
            }

            $tokens->clearAt($secondArgumentIndex);
            $tokens->insertAt($secondArgumentIndex, new Token([T_STRING, 'JSON_OBJECT_AS_ARRAY']));
        }
    }
}
