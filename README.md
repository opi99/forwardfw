ForwardFW
=========

Self written framework which uses chaining RequestResponse filter objects (something like nowadays PSR-3 (Logging
interface) PSR-7 (message abstraction) PSR-15 (middleware)) so I may rewrite it again to match such interfaces.

Compatible to:
--------------

- [PSR-4](http://www.php-fig.org/psr/psr-4/) - Class autoloading
- [PSR-12](http://www.php-fig.org/psr/psr-12/) - Coding Standard

Requirements:
-------------

- PHP 7.2.0 or newer

Supported Template Engines:
---------------------------

- Smarty 3.1.x http://www.smarty.net/
- Twig 3.x.x https://twig.symfony.com/

Supported Remote Loggers:
-------------------------

- FirePHP http://www.firephp.org
- Kodus ChromeLogger https://packagist.org/packages/kodus/chrome-logger
