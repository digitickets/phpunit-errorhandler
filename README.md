[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rquadling/phpunit-errorhandler.svg?style=plastic)](https://scrutinizer-ci.com/g/rquadling/phpunit-errorhandler/?branch=master)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/rquadling/phpunit-errorhandler.svg?style=plastic)](https://scrutinizer-ci.com/coverage/g/rquadling/phpunit-errorhandler/?branch=master)
[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/rquadling/phpunit-errorhandler.svg?style=plastic)](https://scrutinizer-ci.com/build/g/rquadling/phpunit-errorhandler/?branch=master)
[![Travid Build Status](https://img.shields.io/travis/rquadling/phpunit-errorhandler.svg?style=plastic)](https://travis-ci.org/rquadling/phpunit-errorhandler)
[![Latest Stable Version](https://img.shields.io/packagist/v/rquadling/phpunit-errorhandler.svg?style=plastic)](https://packagist.org/packages/rquadling/phpunit-errorhandler)
[![Packagist](https://img.shields.io/packagist/dt/rquadling/phpunit-errorhandler.svg?style=plastic)](https://packagist.org/packages/rquadling/phpunit-errorhandler)

Richard Quadling's PHPUnit ErrorHandler
=======================================

An alternative approach to unit testing PHP errors.

Whilst you can use PHPUnit's built-in behaviour of converting all errors, warnings and notices to exceptions,
this package allows you to track all PHP errors, including user generated errors, separately.

The code is supplied as a trait and is based upon [Error Condition Testing with PHPUnit](http://www.sitepoint.com/testing-error-conditions-with-phpunit/)

Here is an example use case:

```php
<?php
use RQuadling\PHPUnit\ErrorHandler;

class tests extends \PHPUnit_Framework_TestCase
{
    use ErrorHandler;

    protected function setUp()
    {
        // Set the error handler.
        $this->setUpErrorHandler();
    }

    public function testSomething()
    {
        // Run something that will produce an error, warning or notice.
        // For example, a E_USER_NOTICE of 'Incompatible type ignored' can be tested as follows:
        $this->assertError('Incompatible type ignored', E_USER_NOTICE);
    }
}
```
