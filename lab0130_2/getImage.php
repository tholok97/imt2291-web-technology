<?php

// returns image as download of image from disk

require_once 'constants.php';
require_once 'DB.php';

$thumbnail = null;

try {

    $dbh = DB::getDBConnection($database_string);

    $stmt = $dbh->prepare('SELECT * FROM thumbnails WHERE thumbnails.id=:id');
    $stmt->bindParam(':id', $_GET['id']);
    $ok = $stmt->execute();

    if ($ok) {
        $thumbnail = $stmt->fetchAll()[0];
    }

} catch (PDOException $ex) {
    echo 'Coudn\'t load image: ' . $ex->getMessage();
}

if ($ok) {
    header('Content-type: '.$thumbnail['mime']);
    header('Content-Disposition: attachment; filename='.$thumbnail['name']);

    readfile('uploaded_images/' . $thumbnail['id']);
} else {
    echo 'something went wrong..';
}
