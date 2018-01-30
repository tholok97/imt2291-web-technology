<?php

require_once 'DB.php';

// width and height of thumbnail in db
$thumbnail_width = 75;
$thumbnail_height = 75;

// if file was sent -> handle it
if (isset($_FILES['fileToUpload'])) {
    if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {

        // save thumbnailed version to db
        $content = file_get_contents($_FILES['fileToUpload']['tmp_name']);

        // scale down
        $thumbnail = scale(imagecreatefromstring($content), $thumbnail_width, $thumbnail_height);
        unset($content);        // free up space

        // prepare db attributes
        $name = $_POST['name'];
        $mime = $_FILES['fileToUpload']['type'];

        $id = null;

        // save in db
        try {

            $dbh = DB::getDBConnection('mysql:dbname=www_lab0130_2;host=127.0.0.1'); 

            $stmt = $dbh->prepare('INSERT INTO thumbnails (name, content, mime) VALUES (:name, :content, :mime)');

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':content', $thumbnail);
            $stmt->bindParam(':mime', $mime);

            $stmt->execute();

            $id = $dbh->lastInsertId();

        } catch (PDOException $ex) {
            echo 'Something went horribly wrong while inserting in db: ' . $ex->getMessage();
        } 

        // save image to disk
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], 'uploaded_images/' . $id);
    } else {
        echo 'Hackders!!1!';
    }
}

// scales image to new width, new height
// (Taken from Øivind Kolloen (Who took it from the internet))
function scale ($img, $new_width, $new_height) {
    $old_x = imageSX($img);
    $old_y = imageSY($img);

    if($old_x > $old_y) {                     // Image is landscape mode
        $thumb_w = $new_width;
        $thumb_h = $old_y*($new_height/$old_x);
    } else if($old_x < $old_y) {              // Image is portrait mode
        $thumb_w = $old_x*($new_width/$old_y);
        $thumb_h = $new_height;
    } if($old_x == $old_y) {                  // Image is square
    $thumb_w = $new_width;
    $thumb_h = $new_height;
        }

    if ($thumb_w>$old_x) {                    // Don't scale images up
        $thumb_w = $old_x;
        $thumb_h = $old_y;
    }

    $dst_img = ImageCreateTrueColor($thumb_w,$thumb_h);
    imagecopyresampled($dst_img,$img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

    ob_start();                         // flush/start buffer
    imagepng($dst_img,NULL,9);          // Write image to buffer
    $scaledImage = ob_get_contents();   // Get contents of buffer
    ob_end_clean();                     // Clear buffer
    return $scaledImage;
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload"/>
        <input type="text" name="name"/>
        <input type="submit" value="Upload!"/>
    </form>        
</body>
</html>
