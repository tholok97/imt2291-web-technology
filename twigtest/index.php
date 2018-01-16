<?php

require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

$md5Filter = new Twig_SimpleFilter('md5', function($string) {
    return md5($string);
});

$twig->addFilter($md5Filter);

echo $twig->render('hello.html', array(
    'name' => 'Thomas',
    'age' => '20',

    'users' => array(
        array('name' => 'Max', 'age' => 18),
        array('name' => 'James', 'age' => 22),
        array('name' => 'Billy', 'age' => 34),
    ),
));
