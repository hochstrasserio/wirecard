<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Context;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Hochstrasser\Wirecard\Exception\InvalidHashingMethodException
     */
    public function testInvalidHashingMethod()
    {
        $context = new Context(['hashingMethod' => 'foo']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOption()
    {
        $context = new Context(['foo' => 'bar']);
    }
}
