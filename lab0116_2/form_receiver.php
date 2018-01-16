<?php

// db info
$dsn = 'mysql:dbname=www_lab0116;host=127.0.0.1';
$user = 'root';
$password = '';

// try to insert into db
try {

    $pdo = new PDO($dsn, $user, $password);

    $statement = $pdo->prepare('INSERT INTO Contacts (name, email, phone) ' .
        'values (?, ?, ?)');

    // '' should be NULL (so properly handled by db)
    if ($_POST['name'] == '') $_POST['name'] = NULL;
    if ($_POST['email'] == '') $_POST['email'] = NULL;
    if ($_POST['phone'] == '') $_POST['phone'] = NULL;

    $success = $statement->execute(array(
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
    ));

    if (!$success) {
        throw new Exception("Execution of query failed");
    }

    echo '<h1>Added new contact!</h1>';

} catch (Exception $e) {

    // NOTE IKKE Exception DETTE I PRODUKSJON
    echo '<h1>Falied: ' . $e->getMessage() . '</h1>';
}

echo '<a href="./">Return to homepage</a>';
