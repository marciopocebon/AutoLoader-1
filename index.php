<?php

include 'autoloader.php';

$register = autoloader::instance()->register(__DIR__);

/**
 * Created by Ali Hosseini.
 * User: ThinkPad
 * Date: 2018/12/12
 * Time: 3:42 PM
 */

$start = microtime(true);


echo '<br><b>ANIMALS NAMESPACE</b><br><br>';
new animals\bird();
new animals\cat();
new animals\snake();
new animals\dog();

echo '<br><b>CARS NAMESPACE</b><br><br>';
new cars\bmw();
new cars\benz();
new cars\lamborghini();

echo '<br><b>GERMANY CARS NAMESPACE</b><br><br>';
new german_cars\benz();
new german_cars\bmw();

echo '<br><b>WITHOUT NAMESPACE</b><br><br>';
new rootDummy();
new dummy\rootDummy2();

$end = microtime(true) - $start;



echo '<br><br> operation finished in ' . $end . ' milliseconds.';