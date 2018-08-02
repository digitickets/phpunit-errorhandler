<?php

use DigiTickets\PHPUnit\ErrorHandler;

class tests extends \PHPUnit\Framework\TestCase
{
    use ErrorHandler;

    public function provideUserErrors()
    {
        return [
            ['Generated E_USER_ERROR', E_USER_ERROR],
            ['Generated E_USER_WARNING', E_USER_WARNING],
            ['Generated E_USER_NOTICE', E_USER_NOTICE],
            ['Generated E_USER_DEPRECATED', E_USER_DEPRECATED],
        ];
    }

    public function testAssertError()
    {
        $this->expectException(\PHPUnit\Framework\AssertionFailedError::class);
        $this->expectExceptionMessage('Error with level 1024 and message \'Unknown E_USER_WARNING\' not found in array');

        $this->assertError('Unknown E_USER_WARNING', E_USER_NOTICE);
    }

    public function testPHPGeneratedNoticeIsCaptured()
    {
        $keys = [1, 2, 3];
        $keys[1] = $keys[4];

        $this->assertError(
            defined('HHVM_VERSION')
                ? 'Undefined index: 4'
                : 'Undefined offset: 4',
            E_NOTICE
        );
    }

    public function testPHPGeneratedRecoverableErrorIsCaptured()
    {
        $x = function () {
            return 1;
        };
        print (string)$x;
        $this->assertError(
            defined('HHVM_VERSION')
                ? 'Object of class Closure$tests::testPHPGeneratedRecoverableErrorIsCaptured;4 could not be converted to string'
                : 'Object of class Closure could not be converted to string',
            E_RECOVERABLE_ERROR
        );
    }

    public function testPHPGeneratedWarningIsCaptured()
    {
        $keys = [1, 2, 3];
        $values = [1, 2, 3, 4];
        array_combine($keys, $values);

        $this->assertError('array_combine(): Both parameters should have an equal number of elements', E_WARNING);
    }

    /**
     * @dataProvider provideUserErrors
     *
     * @param string $message
     * @param int $errorType
     */
    public function testUserErrorsAreCaptured($message, $errorType)
    {
        trigger_error($message, $errorType);

        $this->assertError($message, $errorType);
    }

    public function testNoErrorsAreCapturedWhenNoErrorsAreGenerated()
    {
        $this->assertNoErrors();
    }

    public function testAssertNoErrorsShouldFailWhenErrorRaised()
    {
        // First error generated
        trigger_error('This is an error.', E_USER_WARNING);

        $this->expectException(\PHPUnit\Framework\AssertionFailedError::class);
        $this->expectExceptionMessage('1 error generated');

        $this->assertNoErrors();

        // Second error generated
        trigger_error('This is another error.', E_USER_WARNING);

        $this->expectException(\PHPUnit\Framework\AssertionFailedError::class);
        $this->expectExceptionMessage('2 errors generated');

        $this->assertNoErrors();
    }
}
