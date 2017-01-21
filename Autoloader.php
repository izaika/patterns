<?php

/**
 * Class Autoloader
 */
class Autoloader
{

	private static $instance;

    /**
     * @var array   Namespace mapping
     */
    protected static $ns_map = [];

    /**
     * Autoloader constructor.
     */
    private function __construct()
    {
    }

	private function __clone()
	{
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init() {
        spl_autoload_register([$this, 'load']);
	}

    /**
     * Register namespace root path
     *
     * @param string $namespace Root namespace
     * @param string $root_path Namespace root path
     */
    public static function addNamespacePath(string $namespace, string $root_path)
    {
        self::$ns_map[$namespace] = $root_path;
    }

    /**
     * Load class
     *
     * @param string $classname Class name
     */
    protected function load(string $classname)
    {
        if ($path = $this->getClassPath($classname)) {
            require_once $path;
        }
    }

    /**
     * Get realpath to the class definition file
     * @param $classname string name of class
     * @return string
     */
    protected function getClassPath(string $classname): string
    {
        $class_path = $classname . '.php';
        if (!empty(self::$ns_map)) {
            foreach (self::$ns_map as $ns => $path) {
                $lookup_pattern = sprintf('/^%s/', $ns);
                if (preg_match($lookup_pattern, $classname)) {
                    $class_path = preg_replace($lookup_pattern, $path, $class_path);
                    break;
                }
            }
        }

        return realpath(str_replace('\\', DIRECTORY_SEPARATOR, $class_path));
    }
}

$autoloader_instance = Autoloader::getInstance();
$autoloader_instance->init();
