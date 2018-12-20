<?php


class autoloader
{

    public static function instance()
    {
        static $instance = false;
        if ($instance === false) {
            // Late static binding
            $instance = new static();
        }

        return $instance;
    }


    /**
     * Register autoloader.
     * @param $directoryLevel
     * @param bool $debug will be try for new php files added in developing time.
     */
    public function register($directoryLevel, $debug = false)
    {
        if ($directoryLevel != null) {
            new spl_registrar($directoryLevel, $debug);
        }
    }

    /**
     * @param $dir_level directory level is for file searching
     * @param $php_files_json_name name of the file who all the PHP files will be stored inside it
     */
    private function export_php_files($dir_level, $php_files_json_name)
    {

        $filePaths = array(mktime());
        /**Get all files and directories using iterator.*/
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir_level));

        foreach ($iterator as $path) {
            if (is_string(strval($path)) and pathinfo($path, PATHINFO_EXTENSION) == 'php') {
                $filePaths[] = strval($path);
            }
        }

        /**Encode and save php files dir in a local json file */
        $fileOpen = fopen($dir_level . DIRECTORY_SEPARATOR . $php_files_json_name, 'w');
        fwrite($fileOpen, json_encode($filePaths));
        fclose($fileOpen);
    }

    /**
     * @param $php_files_json_address json file contains all the PHP files inside it
     * @param $class_file_name name of the class that was taken from @spl_autoload_register plus .php extension
     * @return bool Succeeding end of work
     */
    private function include_matching_files($php_files_json_address, $class_file_name)
    {
        //prevent opening file each time with this global variable
        global $php_files_array;
        $inc_is_done = false;

        if ($php_files_array == null) {
            $php_files_array = json_decode(file_get_contents($php_files_json_address), false);
        }

        /**Include matching files here.*/
        foreach ($php_files_array as $path) {
            if (stripos($path, $class_file_name) !== false) {
                require_once $path;
                $inc_is_done = true;
            }
        }
        return $inc_is_done;
    }

    /**
     * @param $dir_level directory level is for file searching
     * @param $class_name name of the class that was taken from @spl_autoload_register
     * @param bool $try_for_new_files Try again to include new files, that this feature is @true in development mode
     * it will renew including file each time after every 30 seconds @see $refresh_time.
     * @return bool Succeeding end of work
     */
    protected function request_system_files($dir_level, $class_name, $try_for_new_files = false)
    {
        //Applying PSR-4 standard for including system files :
        $php_files_json = 'autoloaderfiles.json';
        $php_files_json_address = $dir_level . DIRECTORY_SEPARATOR . $php_files_json;
        $class_file_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        $files_refresh_time = 30;

        /**Include required php files.*/
        if (is_file($php_files_json_address)) {

            $last_update = json_decode(file_get_contents($php_files_json_address), false)[0];

            if ((mktime() - intval($last_update)) < $files_refresh_time || !$try_for_new_files) {
                return $this->include_matching_files($php_files_json_address, $class_file_name);
            }

        }

        $this->export_php_files($dir_level, $php_files_json);
        return $this->include_matching_files($php_files_json_address, $class_file_name);

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
     */
    private function __sleep()
    {
    }

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup()
    {
    }

}


class spl_registrar extends autoloader
{
    private $directoryLevel;
    private $debug;

    public function __construct($directoryLevel, $debug)
    {
        $this->debug = $debug;
        $this->directoryLevel = $directoryLevel;

        try {
            spl_autoload_register("self::load", true, false);
        } catch (Exception $e) {
            var_dump($e);
            die;
        }

    }

    public function load($className)
    {

        $this->request_system_files(
            $this->directoryLevel,
            $className,
            $this->debug
        );
    }
}

