<?php

use RQuadling\PHPUnit\ErrorHandler;

class tests extends \PHPUnit_Framework_TestCase
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

    public function testAssertErrorHandler()
    {
        $this->errorHandler(E_USER_NOTICE, 'Testing ErrorHandler', __FILE__, __LINE__, []);
        $this->assertError('Testing ErrorHandler', E_USER_NOTICE);
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage Error with level 1024 and message 'Unknown E_USER_WARNING' not found in
     */
    public function testAssertError()
    {
        $this->assertError('Unknown E_USER_WARNING', E_USER_NOTICE);
    }

    public function testPHPGeneratedNoticeIsCaptured()
    {
        $keys = [1, 2, 3];
        $keys[1] = $keys[4];
        $this->assertError('Undefined offset: 4', E_NOTICE);
    }

    public function testPHPGeneratedRecoverableErrorIsCaptured()
    {
        require __DIR__.'/_files/testClassForRecoverableError.php';
        $it = new ArrayIterator(new testClassForRecoverableError());
        $it->append('will not work');
        $this->assertError('ArrayIterator::append(): Cannot append properties to objects, use ArrayIterator::offsetSet() instead', E_RECOVERABLE_ERROR);
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
     * @param int    $errorType
     */
    public function testUserErrorsAreCaptured($message, $errorType)
    {
        trigger_error($message, $errorType);
        $this->assertError($message, $errorType);
    }

    protected function setUp()
    {
        // Set the error handler.
        $this->setUpErrorHandler();
    }
}
