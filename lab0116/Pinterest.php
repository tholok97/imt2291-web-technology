<?php

require_once 'Picture.php';

/**
 * Basert på kode skrevet av forelser Øivind Kolloen
 */

class Pinterest {

    /**
     * Returnerer array med kildene til bildene en får ved et gitt søk på 
     *  pinterest
     */
    public static function getSrc ($searchString) {
        $searchString = str_replace (" ", "%20", $searchString);
        $data = file_get_contents("https://no.pinterest.com/search/pins" .
            "/?q=$searchString");
        //echo $data;
        $dom = new DOMDocument;
        @$dom->loadHTML($data);
        $xpath = new DOMXPath($dom);
        $nlist = $xpath->query("//div[@class='GrowthUnauthPinImage']/a/img");
        $tmp = [];
        foreach($nlist as $img) {
            $srcSet =  $img->getAttribute('srcset');
            $srcSet = explode(", ", $srcSet);
            foreach ($srcSet as $key => $value) {
                $srcSet[$key] = trim(substr($value, 0, strlen($value)-3));
            }
            $tmp[] = $srcSet[0];
        }
        return $tmp;
    }

    public static function getPicture($searchString) {
        $searchString = str_replace (" ", "%20", $searchString);
        $data = file_get_contents("https://no.pinterest.com/search/pins" .
            "/?q=$searchString");
        //echo $data;
        $dom = new DOMDocument;
        @$dom->loadHTML($data);
        $xpath = new DOMXPath($dom);
        $nlist = $xpath->query("//div[@class='GrowthUnauthPinImage']/a/img");
        $tmp = [];
        
        foreach($nlist as $img) {
            $pic = new Picture();
            $pic->source = $img->getAttribute('src');
            $pic->text = $img->getAttribute('alt');
            $pic->pinterestLink = 'https://no.pinterest.com' . $img->parentNode->getAttribute('href');
            array_push($tmp, $pic);
        }
        return $tmp;
    }
}
