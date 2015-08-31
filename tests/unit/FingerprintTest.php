<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Context;

class FingerprintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \UnexpectedValueException
     */
    function testFromResponseParametersWithoutResponseFingerprintOrderThrowsException()
    {
        Fingerprint::fromResponseParameters([]);
    }

    function testFromResponseParameters()
    {
        $message = [
            'foo' => 'foo',
            'bar' => 'bar',
            'secret' => 'secret',
            'responseFingerprintOrder' => 'foo,bar,secret,responseFingerprintOrder',
        ];

        $expected = hash('sha512', join('', $message));
        $fingerprint = Fingerprint::fromResponseParameters($message);

        $this->assertEquals($expected, (string) $fingerprint);
    }

    function testFromResponseParametersTakesSecretFromContext()
    {
        $context = new Context(['secret' => 'secret']);

        $message = [
            'foo' => 'foo',
            'bar' => 'bar',
            'responseFingerprintOrder' => 'foo,bar,secret,responseFingerprintOrder',
        ];

        $expected = hash('sha512', $message['foo'].$message['bar'].$context->getSecret().$message['responseFingerprintOrder']);
        $fingerprint = Fingerprint::fromResponseParameters($message, $context);

        $this->assertEquals($expected, (string) $fingerprint);
    }
}
