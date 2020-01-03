<?php
if (isset($_GET['term'])) {
    $term = urlencode($_GET['term']);
    if (!isset($_GET['disableLink']))
        if (strpos($term, "store.steampowered.com") !== FALSE) {
            //$pattern = "!<a aria-hidden=\"true\" href=\"/watch\?v=([^\"]+)!is";
            $pattern = "!store\.steampowered\.com/([^/]+)/([0-9]+)/!is";
            $term    = urldecode($term);
            preg_match($pattern, $term, $rest);
            //print_r($rest);
            echo "<a class=\"match ds_collapse_flag \"  data-ds-$rest[1]id=\"$rest[2]\"><div class=\"match_name\">Linked Game</div><div class=\"match_img\"><img src=\"http://cdn.akamai.steamstatic.com/steam/$rest[1]s/$rest[2]/capsule_sm_120.jpg\">";
            //<a class=\"match ds_collapse_flag \"  data-ds-([a-z]+)id=\"([0-9]+)\"[^>]*\><div class=\"match_name\">([^>]*)</div><div class=\"match_img\"><img src=\"([^\"]*)\">
            die;
        }
    $cc = $_GET['cc'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_COOKIE,'birthtime=157795201;lastagecheckage=1-January-1975'); 
    curl_setopt($ch, CURLOPT_URL, "https://store.steampowered.com/search/suggest?term=$term&f=games&cc=$cc&l=italian");
    $data = curl_exec($ch);
    if (strpos(strtolower($term), "anno") !== false)
        echo "<a class=\"match\" href=\"https://store.steampowered.com/sub/26683/\"><div class=\"match_name\">Anno 2070 Complete Edition</div><div class=\"match_img\"><img src=\"http://cdn.akamai.steamstatic.com/steam/subs/26683/capsule_sm_120.jpg\"></div></a>";
    if (strpos(strtolower($term), "metro") !== false)
        echo "<a class=\"match\" href=\"https://store.steampowered.com/sub/39286/\"><div class=\"match_name\">Metro Last Light Complete</div><div class=\"match_img\"><img src=\"http://cdn.akamai.steamstatic.com/steam/subs/39286/capsule_sm_120.jpg\"></div></a>";
    if ($data == "") {
        $term = strtolower($term);
        if (strpos($term, "goty") !== false) {
            $term = str_replace("goty", "Game+Of+The+Year", $term);
            curl_setopt($ch, CURLOPT_URL, "https://store.steampowered.com/search/suggest?term=$term&f=games&cc=$cc&l=italian&v=543453");
            $data = curl_exec($ch);
        } else if (strpos($term, "game+of+the+year+edition") !== false) {
            $term = str_replace("game+of+the+year+edition", "Game+Of+The+Year", $term);
            curl_setopt($ch, CURLOPT_URL, "https://store.steampowered.com/search/suggest?term=$term&f=games&cc=$cc&l=italian&v=543453");
            $data = curl_exec($ch);
        } else if (strpos($term, "bioshock+triple") !== false)
            echo "<a class=\"match\" href=\"https://store.steampowered.com/sub/36223/?snr=1_7_15__13\"><div class=\"match_name\">BioShock Triple Pack</div><div class=\"match_img\"><img src=\"http://cdn.akamai.steamstatic.com/steam/subs/36223/capsule_sm_120.jpg\"></div></a>";
    }
    curl_close($ch);
    echo $data;
}
?>