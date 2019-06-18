<?php
/**
 * Created by Ali Hosseini.
 * User: ThinkPad
 * Date: 2018/12/12
 * Time: 3:42 PM
 */

$start = microtime(true);

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
new Autoload\animals\bird();
new Autoload\animals\cat();
new Autoload\animals\snake();
new Autoload\animals\dog();

echo '<br><b>CARS NAMESPACE</b><br><br>';
new Autoload\cars\bmw();
new Autoload\cars\benz();
new Autoload\cars\lamborghini();

echo '<br><b>GERMANY CARS NAMESPACE</b><br><br>';
new Autoload\german_cars\benz();
new Autoload\german_cars\bmw();

echo '<br><b>WITHOUT NAMESPACE</b><br><br>';
new Autoload\rootDummy();
new Autoload\dummy\rootDummy2();

$end = microtime(true) - $start;



echo '<br><br> operation finished in ' . $end . ' milliseconds.';