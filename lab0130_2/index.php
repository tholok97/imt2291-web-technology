<!-- handle form -->
<?php

require_once 'DB.php';
require_once 'constants.php';


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

            $dbh = DB::getDBConnection($database_string); 

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

<!-- show images from db -->
<?php
    
    // get images from db
    $dbh = DB::getDBConnection($database_string);

    $thumbnails = $dbh->query('SELECT * FROM thumbnails ORDER BY thumbnails.id');

    // list them
    foreach ($thumbnails as $thumbnail) {

        echo '
            <div style="border:1px solid black;padding:20px;margin:30px">
                <h3>'.$thumbnail['name'].'</h3>
                <img src="getThumbnail.php?id='.$thumbnail['id'].'"/>
                <a href="getImage.php?id='.$thumbnail['id'].'">Download!</a>
            </div>
        ';
    }
