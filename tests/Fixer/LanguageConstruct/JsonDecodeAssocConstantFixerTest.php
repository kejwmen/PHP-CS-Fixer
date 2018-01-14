<?php
declare(strict_types=1);

namespace PhpCsFixer\Tests\Fixer\LanguageConstruct;
use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @author Mateusz Sip <mateusz.sip@gmail.com
 *
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\LanguageConstruct\JsonDecodeAssocConstantFixer
 */
final class JsonDecodeAssocConstantFixerTest extends AbstractFixerTestCase
{
    /**
     * @param string      $expected
     * @param null|string $input
     *
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    public function provideFixCases()
    {
        return [
            [
                '<?php json_decode($test, JSON_OBJECT_AS_ARRAY) ?>',
                '<?php json_decode($test, true) ?>'
            ],
            [
                '<?php json_decode($test, JSON_OBJECT_AS_ARRAY) ?>',
                '<?php json_decode($test, 1) ?>'
            ],
        ];
    }
}
