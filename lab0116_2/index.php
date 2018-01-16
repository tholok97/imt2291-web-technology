<?php

require_once 'vendor/autoload.php';
require_once 'Contact.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);


$page = 'show_contacts';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

try {


    if ($page == 'show_contacts') {

        $contacts = loadContacts();

        echo $twig->render($page . '.twig', array(
            'contacts' => $contacts,
        ));
    } else {
        echo $twig->render($page . '.twig', array());
    }
} catch (PDOException $ex) {
    echo "<h1>Something went wrong with the DB: " . $ex->getMessage() . "</h1>";
} catch (Exception $ex) {
    echo "<h1>404 - Page '" . $page . "' not found</h1>";
}
