# AutoLoader
PHP PSR-4 standard file scanner and autoloader system that loads class files recursively.


### Purpose

There are several nice fully-featured, [PSR-0](http://www.php-fig.org/psr/psr-0/)/[PSR-4]
(http://www.php-fig.org/psr/psr-4/) compliant autoloaders out there.  Many
packages such as [Composer]() come with them pre-packaged, but they can also be
used independently.  Why create yet another with so many truly nice options
available?  Simplicity.  I wanted something simple and basic that I could drop
into any project with a basic directory structure and be up and running without
the complexities that come with other solutions.  I also wanted something that
I could release to the Open Source community that would be simple enough for
beginners to use and understand.  If you're using Composer or Laravel or some
other package that includes an autoloader, you should use it.  This is for the
times when you're not.

### Target audience and prerequisites
This project is aimed at programmers with a basic knowledge of PHP and MySQL.
It assumes that you already have:
* A web server running PHP
* A basic understanding of general programming concepts and PHP syntax

## A Brief Explanation

This class allows you to instanciate objects and classes without include or import it at the top of
scripts, instead of it you can just include autoloader class and then make an instance of it, register a directory level to scan php files, after registration this class will be include php classes or files automatically each time. 

### What is autoloading?
Class autoloading, typically referred to simply as "autoloading", is a method
of including necessary class files in a project dynamically at runtime as
opposed to hard-coding an include statement for each class file dependency in
every file.  This allows for faster development and less bloated files.

### PHP support for autoloading
PHP 5 added support for autoloading with the introduction of the `__autoload()`
function.  The `spl_autoload_register()` function added in PHP 5.1.2 provided
more flexibility and is considered to be the proper method of implementing
autoloading in PHP today.  More recently, PHP 5.3 has added support for
namespaces.

## Requirements

* **PHP 5.3+**, Minimum PHP 5.1.2

## Live Demo
A working demo of this code is provided in the sample directory.  Upload this
directory to your webserver and access the index.php page in your web browser.

## Disclaimer

The code presented here is fully functioning and should not present any issues
when used in a production environment.  However, server environments and coding
practices differ widely and this code should be considered for educational
purposes only.

## Contribute

Suggestions and pull requests are always welcome.

#### USAGE

An example implementation can be found in the sample directory.  The
*autoloader.php* file can be placed the topmost directory of your project,
For best performance, it's best to keep all of your class files in a separate directory
away from the rest of your project.  This helps to limit unnecessary overhead
caused by traversing the file system.

Once the *autoloader.php* file is in place, include it once near the top of your
current PHP file.

```php
<?php

// include the autoloader class near the top of your page
      include 'autoloader.php';
      $autoload = autoloader::instance();
      $autoload->register(__DIR__);// directory level
      $autoload->register(ANOTHER__DIR__);// directory level
      $autoload->register(ANOTHER__DIR__);// directory level
      ...

```
#### ATTENTION : You can register a lot of dir levels, but note that it will cost you a lot!
Because this will open and read the files of each level of the directories.

### $autoload->register() Params:

1 - String dir_level : As i said indicates your directory level 

2 - Bool debug mode : If you're developing, put this function input *`true`*
this will cause the files to be scanned every 10 seconds

*`***Remember to change it to false in production time***`*

3 - String file_extension : By default, the autoloader assumes that you are using the *`classname.php`* file
naming convention.  In other words, the class *`myobject`* would be found in a
file named *`myobject.php`*.  Support for other naming conventions is available.
If your naming convention uses file names such as *`myobject.class`* or *`myobject.class.php`*, you can change the
default file extension to *`.class.php`*! .


Classes can then be loading dynamically without any additional include
statements as long as the class file is found in the same directory level *`__DIR__`* or, in 
another directory, you have registered it.

  for example you need to include files before call these calsses or functions : 

```php
    <?php
    
          include 'rootDummy.php';
          include 'cars/benz.php';
          include 'cars/bmw.php';
          include 'cars/lamborghini.php';
          include 'german_cars/bmw.php';
          include 'german_cars/benz.php';
          include 'animals/cat.php';
          include 'animals/dog.php';
          include 'animals/bird.php';
          include 'animals/snake.php';
          include 'dummy/rootDummy2.php';
          echo '<br><b>ANIMALS NAMESPACE</b><br><br>';
          new \animals\bird();
          new \animals\cat();
          new \animals\snake();
          new \animals\dog();
          echo '<br><b>CARS NAMESPACE</b><br><br>';
          new \cars\bmw();
          new \cars\benz();
          new \cars\lamborghini();
          echo '<br><b>GERMANY CARS NAMESPACE</b><br><br>';
          new \german_cars\benz();
          new \german_cars\bmw();
          echo '<br><b>WITHOUT NAMESPACE</b><br><br>';
          new rootDummy();
          new rootDummy2();
 ```
 but you can optimize and handle it without include un used files by using the auto loader like this :

```php
      <?php
      
            include 'autoloader.php';
            $autoload = autoloader::instance();
            $autoload->register(__DIR__);// directory level

            echo '<br><b>ANIMALS NAMESPACE</b><br><br>';
            new \animals\bird();
            new \animals\cat();
            new \animals\snake();
            new \animals\dog();
            echo '<br><b>CARS NAMESPACE</b><br><br>';
            new \cars\bmw();
            new \cars\benz();
            new \cars\lamborghini();
            echo '<br><b>GERMANY CARS NAMESPACE</b><br><br>';
            new \german_cars\benz();
            new \german_cars\bmw();
            echo '<br><b>WITHOUT NAMESPACE</b><br><br>';
            new rootDummy();
            new rootDummy2(); 
      
```

## Useful links

- [Autoloading Classes (PHP Manual)](http://php.net/manual/en/language.oop5.autoload.php)
- [PSR-Huh?](http://code.tutsplus.com/tutorials/psr-huh--net-29314)
- [Practical Guide To PSR-0 and PSR-4](http://engineeredweb.com/blog/2014/practical-guide-psr0-psr4/)
- [Battle of the Autoloaders: PSR-0 vs. PSR-4](http://www.sitepoint.com/battle-autoloaders-psr-0-vs-psr-4/)

      
## License

The MIT License (MIT)

Copyright (c) 2018 Ali Hosseini

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

**[http://opensource.org/licenses/MIT](http://opensource.org/licenses/MIT)**

