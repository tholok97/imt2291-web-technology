<?php

require_once 'vendor/autoload.php';
require_once 'Pinterest.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

echo $twig->render('pictureDisplay.twig', array(
    'sources' => Pinterest::getPins('Sunnmøre'),
));
