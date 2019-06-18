<?php 
   
/**
 * @generated autoloader_config.php by Autoloader
 * Class autoloader_config_md5(/home/ali/PhpstormProjects/wp.localhost/Autoload)
 * its a unique class just for this directory level, The Autoloader 
 * uses this class so DO NOT REMOVE IT!
 */
class autoloader_config_b0cf2f3f5b2940ee88be781cc9f0ae76    
{

	/**
	 * @return array sorted files in this directory level
	 * @link https://php.net/manual/en/function.natcasesort.php
	 */
    public static function get_files()
    {
        return array(
             1560887342,
            "/Autoload/animals/bird.php",
            "/Autoload/animals/cat.php",
            "/Autoload/animals/dog.php",
            "/Autoload/animals/snake.php",
            "/Autoload/autoloader.php",
            "/Autoload/cars/benz.php",
            "/Autoload/cars/bmw.php",
            "/Autoload/cars/lamborghini.php",
            "/Autoload/dummy/rootDummy2.php",
            "/Autoload/german_cars/benz.php",
            "/Autoload/german_cars/bmw.php",
            "/Autoload/index.php",
            "/Autoload/index_old.php",
            "/Autoload/index_old.php",
            "/Autoload/rootDummy.php"
        );
    }

	/**
	 * This function searches the required file with binary algorithm
	 * @param $file_name string search file name
	 * @return bool|mixed Filename or unsuccessful search result.
	 * @link https://php.net/manual/en/function.natcasesort.php
	 * @link https://php.net/manual/en/function.strnatcasecmp.php
	 */
    public static function in_files($file_name)
    {
        $files = self::get_files();

        // starting a binary search
        $left = 0;
        $right = count($files) - 1;

        while ($left <= $right) {
            $midpoint = $left + intval(($right - $left) / 2);

            if (strnatcasecmp($files[$midpoint], $file_name) < 0) {
                $left = $midpoint + 1;
            } elseif (strnatcasecmp($files[$midpoint], $file_name) > 0) {
                $right = $midpoint - 1;
            } else {
                return $files[$midpoint];
            }
        }

        return false;
    }
}