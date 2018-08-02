<?php

namespace DigiTickets\PHPUnit;

/**
 * Class ErrorHandling.
 *
 * To activate PHPUnit error handling:
 * 1. Add 'use \DigiTickets\PHPUnit\ErrorHandler;' to your unit test.
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
     * @param int $errno
     */
    public function assertError(string $errstr, int $errno)
    {
        foreach ($this->errors as $error) {
            if ($error['errstr'] === $errstr && $error['errno'] === $errno) {
                $this->assertTrue(true);

                return;
            }
        }
        $this->fail("Error with level {$errno} and message '{$errstr}' not found in ".var_export($this->errors, true));
    }

    /**
     * Assert that no errors were generated.
     */
    public function assertNoErrors()
    {
        $this->assertEmpty($this->errors, sprintf('$s errors generated.', number_format(count($this->errors))));
    }

    /**
     * Activate PHPUnit error handler.
     *
     * @before
     */
    protected function setUpErrorHandler()
    {
        $this->errors = [];
        set_error_handler(
            function (int $errno, string $errstr, string $errfile, int $errline, array $errcontext) {
                $this->errors[] = [
                    'errno' => $errno,
                    'errstr' => $errstr,
                    'errfile' => $errfile,
                    'errline' => $errline,
                    'errcontext' => $errcontext,
                ];
            },
            -1
        );
    }
}
