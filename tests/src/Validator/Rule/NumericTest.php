<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Tests\Validator\Rule;

use Bluz\Tests;
use Bluz\Validator\Rule\Numeric;

/**
 * Class NumericTest
 * @package Bluz\Tests\Validator\Rule
 */
class NumericTest extends Tests\TestCase
{
    /**
     * @var Numeric
     */
    protected $validator;

    /**
     * Setup validator instance
     */
    protected function setUp()
    {
        parent::setUp();
        $this->validator = new Numeric;
    }

    /**
     * @dataProvider providerForNumeric
     *
     */
    public function testNumeric($input)
    {
        $this->assertTrue($this->validator->validate($input));
    }

    /**
     * @dataProvider providerForNotNumeric
     */
    public function testNotNumeric($input)
    {
        $this->assertFalse($this->validator->validate($input));
    }

    /**
     * @return array
     */
    public function providerForNumeric()
    {
        return array(
            array(165),
            array(165.0),
            array(-165),
            array('165'),
            array('165.0'),
            array('+165.0'),
        );
    }

    /**
     * @return array
     */
    public function providerForNotNumeric()
    {
        return array(
            array(null),
            array('a'),
            array(''),
            array(' '),
            array('Foo'),
        );
    }
}
