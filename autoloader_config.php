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
             1560879758,
            "/animals/bird.php",
            "/animals/cat.php",
            "/animals/dog.php",
            "/animals/snake.php",
            "/autoloader.php",
            "/cars/benz.php",
            "/cars/bmw.php",
            "/cars/lamborghini.php",
            "/dummy/rootDummy2.php",
            "/german_cars/benz.php",
            "/german_cars/bmw.php",
            "/index.php",
            "/index_old.php",
            "/index_old.php",
            "/rootDummy.php"
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