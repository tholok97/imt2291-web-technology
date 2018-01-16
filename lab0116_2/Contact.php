<?php

class Contact {
    public $name;
    public $email;
    public $phone;
    public function __construct($name, $email, $phone) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
}

function loadContacts() {

    $dsn = 'mysql:dbname=www_lab0116;host=127.0.0.1';
    $user = 'root';
    $password = '';

    $pdo = new PDO($dsn, $user, $password);

    $statement = $pdo->prepare('SELECT * FROM Contacts');

    $contacts = array();
    foreach ($pdo->query('SELECT * FROM Contacts') as $row) {
        array_push($contacts, new Contact($row['name'], $row['email'], $row['phone']));
    }

    return $contacts;
}
