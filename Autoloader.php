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
    protected $ns_map = [];

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
    public function addNamespacePath(string $namespace, string $root_path)
    {
        $this->ns_map[$namespace] = $root_path;
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
        if (!empty($this->ns_map)) {
            foreach ($this->ns_map as $ns => $path) {
                $lookup_pattern = sprintf('/^%s/', $ns);
                if (preg_match($lookup_pattern, $classname)) {
                    $class_path = preg_replace($lookup_pattern, $path, $class_path);
                    var_dump($class_path);
                    break;
                }
            }
        }

        $realpath_value = realpath(str_replace('\\', DIRECTORY_SEPARATOR, $class_path));

		var_dump($realpath_value);
        return $realpath_value;
    }
}

$autoloader_instance = Autoloader::getInstance();
$autoloader_instance->init();
