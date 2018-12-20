# AutoLoader
PHP PSR-4 standard file scanner and autoloader system .


This class allows you to instanciate objects and classes without include or import it at the top of
scripts, instead of it you can include autoloader class and make an instance of it, then register a directory level to scan 
php files, after registration this class will be include php classes or files automatically. 

  for example you need to include files before call these calsses or functions : 
  
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
 
 but you can optimize and handle it without include un used files by using the auto loader like this :

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
