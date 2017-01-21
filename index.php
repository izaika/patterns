<?php

use Exceptions\IncorrectAdapterNameException;
use Exceptions\ParametersParseException;

require_once "Autoloader.php";

// Example of usage addNamespacePath:
// Autoloader::addNamespacePath('MindK', 'vendor/mindk/src');
$autoloader_class = Autoloader::getInstance();
$autoloader_class->addNamespacePath('TestClass', 'External/Mindk/Superpuperpackage/TestClass');
var_dump($autoloader_class);

try {
    $config = ConfigSingleton::getInstance();
    $database_factory = new DatabaseAdapterFactory();
    $adapter = $database_factory->getAdapter($config->get("database")["adapter"]);
    debug($adapter);
} catch (ParametersParseException $e) {
    echo $e->getMessage();
} catch (IncorrectAdapterNameException $e) {
    echo $e->getMessage();
}

$test_class = new TestClass();
$test_class->echoSomething('test');


function debug($value)
{
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}