@echo off

..\..\bin\php\php7.0.10\php.exe ..\phpunit.phar %* --coverage-html cover --colors="always"
