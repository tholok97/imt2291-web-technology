<?php

require_once 'Picture.php';
require_once 'vendor/autoload.php';
require_once 'Pinterest.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$pictures = Pinterest::getPicture('Sunnmøre');

echo $twig->render('pictureDisplay.twig', array(
    'pictures' => $pictures,
));
