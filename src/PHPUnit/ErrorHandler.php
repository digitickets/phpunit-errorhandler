<?php

namespace RQuadling\PHPUnit;

/**
 * Class ErrorHandling.
 *
 * To activate PHPUnit error handling:
 * 1. Add 'use \DigiTickets\PHPUnit\ErrorHandler;' to your unit test.
 * 2. Call $this->setUpErrorHandler(); from within the unit test's setUp() method.
 *
 * This code is based upon http://www.sitepoint.com/testing-error-conditions-with-phpunit/
 */
trait ErrorHandler
{
    /**
     * @var mixed[]
     */
    private $errors;

    /**
     * Assert that the requested error was generated.
     *
     * @param string $errstr
     * @param int    $errno
     */
    public function assertError($errstr, $errno)
    {
        foreach ($this->errors as $error) {
            if ($error['errstr'] === $errstr && $error['errno'] === $errno) {
                return;
            }
        }
        $this->fail("Error with level {$errno} and message '{$errstr}' not found in ", var_export($this->errors, true));
    }

    /**
     * Error handler for PHPUnit.
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     * @param array  $errcontext
     */
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->errors[] = compact('errno', 'errstr', 'errfile', 'errline', 'errcontext');
    }

    /**
     * Activate PHPUnit error handler.
     */
    protected function setUpErrorHandler()
    {
        $this->errors = [];
        set_error_handler([$this, 'errorHandler'], -1);
    }
}
