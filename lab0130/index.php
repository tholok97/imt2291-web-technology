<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <form action="" method="post">
        <input type="url" name="url" />
        <input type="submit" value="Submit!" />
    </form>
</body>
</html>

<?php

// if rss field was sent to the server, parse rss and present clickable links 
// to the user
if (isset($_POST['rss'])) {
    
    // get
    $doc = new DOMDocument();
    $doc->load($_POST['rss']);
    $xpath = new DOMXpath($doc);
    
    // parse
    $newsItems = $xpath->query('//item');

    // present
    foreach($newsItems as $item) {

        // fetch title, description and link to news article
        $title = $item->getElementsByTagName('title')->item(0)->nodeValue;
        $description = $item->getElementsByTagName('description')->item(0)->nodeValue; 
        $link = $item->getElementsByTagName('link')->item(0)->nodeValue; 
        $image = @$item->getElementsByTagName('image')->item(0)->nodeValue; 


        // if no description -> replace with message about that
        if ($description == '') {
            $description = '<it>(No description)</it>';
        }

        // show articles
        echo "
        <div style='margin:50px;border:1px solid black;padding:20px'>
            <h3>$title</h3>
            <img src='$image' alt='(No image)'/>
            <p>$description</p>
            <a href='$link'>Go to article!</a> 
        </div>
        ";
    }

} else if (isset($_POST['url'])) {
    // if form with url field was sent to the server, handle it
    // ->   that is, find all rss feeds in html pointed to by url, extract the topics 
    //      and display them as clickable titles

    // get xpath to page
    $doc = new DOMDocument();

    @$doc->loadHTMLFile($_POST['url']);
    $xpath = new DOMXpath($doc);

    // crawl page for rss stuff
    // search for: <link rel="alternate" type="application/rss+xml" title="......." href=".......">
    $elements = $xpath->query("//link[@rel='alternate' and @type='application/rss+xml']");

    // show each rss feed as clickable button
    foreach ($elements as $element) {
        
        echo '
            <form method="post">
                <input type="hidden" name="rss" value="'. $element->getAttribute('href') .'"/>
                <input type="submit" value="'. $element->getAttribute('title') .'"/>
            </form>
        ';

    }
}

?>
