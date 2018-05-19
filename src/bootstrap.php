<?php

// Load our autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Specify our Twig templates location
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/../views');
$twig = new Twig_Environment(
    $loader, array(
        //'cache' => './compilation_cache',
        'debug' => true,
    )
);

// Instantiate our Twig
$twig = new Twig_Environment($loader);

$twig->addExtension(new Twig_Extension_Debug());
