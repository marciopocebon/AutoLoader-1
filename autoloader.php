<?php
//define default json file name who contains all the PHP files inside it.
define('AUTOLOAD_CONFIG_PHP', 'autoloader_config.php');

class autoloader
{

    static $instance = false;

    /**
     * @return autoloader singleton instance of the class
     */
    public static function instance()
    {
        if (self::$instance === false) {
            $instance = new self();
        }

        /** @var autoloader $instance */
        return $instance;
    }

    /**
     * Register autoloader.
     *
     * @param $directoryLevel string level of the directory and all of the files
     * inside this level will be scanned and stored in the autoloader config file
     *
     * @param bool $debugMode : If this value is set true, when the autoloader
     * failed to including required file, it will refresh and re-create config file after every 10 seconds.
     * This option is for development time , because files are added and deleted many times
     * during development. It is important that you change the value to false after the development!
     *
     * @param string $fileExtension specific file Extension
     *
     * @return spl_registrar|null @see spl_registrar class
     */
    public function register($directoryLevel, $debugMode = false, $fileExtension = ".php")
    {
        if ($directoryLevel != null) {
            return new spl_registrar($this, $directoryLevel, $debugMode, $fileExtension);
        }

        return null;
    }

    /**
     * @param $dir_level : directory level is for file searching
     * @param $file_extension : our specific extension for files Default is .php
     */
    private function export_php_files($dir_level, $file_extension)
    {

        $file_paths = array();

        /**Get all files and directories using recursive iterator.*/
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir_level, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        /**Add all of iterated files in array */
        while ($iterator->valid()) {

            $path = strval($iterator->current());

            if (stripos(pathinfo($path, PATHINFO_BASENAME), $file_extension)) {
                $file_paths[] = str_replace(dirname($dir_level, 1), '', $path);
            }
            //while have next maybe throw an exception.
            try {
                $iterator->next();
            } catch (Exception $ignored) {
                var_dump($ignored);
                break;
            }
        }
        $this->write_config($dir_level, $file_paths);
    }

    /**
     * @generates AUTOLOAD_CONFIG_PHP file
     * @param $dir_level string : directory level
     * @param $file_paths array : natural case sorted file names
     * @return bool : reports succeeding end of work
     */
    private function write_config($dir_level, $file_paths)
    {

        natcasesort($file_paths);

        $fs = fopen($dir_level . DIRECTORY_SEPARATOR . AUTOLOAD_CONFIG_PHP, "w");
        if (!is_resource($fs)) {
            return false;
        }
        fwrite($fs, '<?php 
   
/**
 * @generated ' . AUTOLOAD_CONFIG_PHP . ' by Autoloader
 * Class autoloader_config_md5(' . $dir_level . ')
 * its a unique class just for this directory level, The Autoloader 
 * uses this class so DO NOT REMOVE IT!
 */
class autoloader_config_' . md5($dir_level) . '    
{

	/**
	 * @return array sorted files in this directory level
	 * @link https://php.net/manual/en/function.natcasesort.php
	 */
    public static function get_files()
    {
        return array(
             ' . time() . ',
            "' . implode('",' . PHP_EOL . '            "', $file_paths) . '"
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
}');

        fclose($fs);

        return true;
    }

    /**
     * @param $dir_level string : directory level
     * @param $class_file_name string : name of the class that was taken from @spl_autoload_register plus .php extension
     * @param $config_file string : config file directory
     * @return bool : reports succeeding end of work
     */
    private function include_matching_files($dir_level, $config_file, $class_file_name)
    {
        $inc_is_done = false;

        if (is_file($config_file) && is_readable($config_file)) {

            $result = call_user_func('autoloader_config_' . md5($dir_level) . '::in_files', $class_file_name);
            if ($result) {
                /** @noinspection PhpIncludeInspection */
                require_once dirname($dir_level, 1) . $result;
                $inc_is_done = true;
            }
        }

        return $inc_is_done;
    }

    /**
     * @param $dir_level : directory level is for file searching
     * @param $class_name : name of the class that was taken from @spl_autoload_register
     * @param bool $try_for_new_files : If this value is set true, when the autoloader
     * failed to including required file, it will refresh and re-create config file after
     * every 10 seconds @see $files_refresh_time.
     * This option is for development time , because files are added and deleted many times
     * during development. It is important that you change the value to false after the development!
     * @return bool : reports succeeding end of work
     */
    public function request_system_files($dir_level, $class_name, $try_for_new_files, $file_extension)
    {
        //Applying PSR-4 standard for including system files :
        $php_files_config = $dir_level . DIRECTORY_SEPARATOR . AUTOLOAD_CONFIG_PHP;
        $class_file_name = DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . $file_extension;
        $files_refresh_time = 10;

        /**Exporting required php files.*/
        if (!is_file($php_files_config)) {
            $this->export_php_files($dir_level, $file_extension);
        }

        /** @noinspection PhpIncludeInspection */
        require_once $php_files_config;

        $found = $this->include_matching_files($dir_level, $php_files_config, $class_file_name);

        if (!$found && $try_for_new_files) {
            $updated_at = call_user_func('autoloader_config_' . md5($dir_level) . '::get_files')[0];
            if ((time() - intval($updated_at)) > $files_refresh_time) {
                $this->export_php_files($dir_level, $file_extension);
                $found = $this->include_matching_files($dir_level, $php_files_config, $class_file_name);
            }
        }

        return $found;
    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct()
    {
    }

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone()
    {
    }

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function __sleep()
    {
    }

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function __wakeup()
    {
    }

}

/**
 * @spl_registrar this object is a registrar for spl_autoload_register
 * with @autoloader system
 */
class spl_registrar
{

    /**
     * @var autoloader
     */
    private $autoloader = null;
    /**
     * @var string registration level
     */
    private $directoryLevel = null;
    /**
     * @var string $file_extension normally is .php but can be a custom file extension
     */
    private $file_extension;
    /**
     * @var bool $debug Specifies the debug mode, @see $try_for_new_files.
     */
    private $debug;

    function __construct($autoloader, $directoryLevel, $debugMode, $fileExtension)
    {
        $this->autoloader = $autoloader;
        $this->debug = $debugMode;
        $this->directoryLevel = $directoryLevel;
        $this->file_extension = $fileExtension;

        try {
            spl_autoload_register(array($this, 'load'), true, false);
        } catch (Exception $e) {
            var_dump($e);
            die;
        }

    }

    /**
     * This function turns this class loader into an unregistered @see spl_classes
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load'));
    }

    /**
     * @param $className string class name
     * @return bool succeeding of loader
     */
    protected function load($className)
    {

        if (is_null($this->autoloader) || is_null($this->directoryLevel)) {
            return false;
        }

        return $this->autoloader->request_system_files(
            $this->directoryLevel,
            $className,
            $this->debug,
            $this->file_extension
        );
    }

}

