<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" href="favicon.png">
		<link rel="shortcut icon" type="image/png" href="favicon.png">
		<title>SpazioPC OP Maker: Remastered</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
		<style>
			.box_vd{
				float: left;
				padding: 20px;
				width: 16.6%;
				box-sizing: border-box;
				font-size: 0.8em;
			}
			.active{
				background-color: #71a231;
				border-radius: 5px;
			}
		</style>
		<script src="script.js"></script>
		<script type="text/javascript">
			function selectText(id)	{
				document.getElementById(id).focus();
				document.getElementById(id).select();
				document.execCommand('copy');
			}
		</script>
	</head>
	<body>
		<div class="main clearfix">
			<h1><strong>SpazioPC OP Maker</strong>: Remastered</h1>
			<p>Cerca e seleziona il gioco di cui vuoi generare l'open post, quindi clicca su <strong>Genera OP</strong>.<br>Potrai incollare il risultato direttamente sul forum.</p>
			<p>Sul fondo potrai inoltre selezionare un altro video YouTube diverso da quello impostato automaticamente.</p>
			<form id="form_gift" action="" method="POST">
				<div class="form clearfix">
					<div class="ricerca">
						<p><strong>Ricerca Steam</strong></p>
						<input class="g_name" id="g_name" autocomplete="off" name="g_name" onClick="this.select();" type=text placeholder="Nome del gioco" onkeypress="getSuggest();">
						<!-- <br> -->
						<!-- <p>Ricerca Origin</p> -->
						<input class="g_name" id="g_name_or" autocomplete="off" name="g_name_or" onClick="this.select();" type="hidden" placeholder="Ricerca gioco..." onkeypress="getSuggestOrigin();">
						<br>
						<input type="hidden" id="appid" name="appid">
						<input type="hidden" id="link_img" name="link_img">
						<select id="sr_list" onclick = required onclick = "if (typeof(this.selectedIndex) != 'undefined') checksel_game(this.selectedIndex);">
						</select>
					</div>
					<div class="bottone">
						<div class="overlay_game" id="overlay_game">
							<div id="title">
								Nessun titolo selezionato
							</div>
							<div>
								<img id="titleimg" src="noimage.png">
							</div>
						</div>
						<div class="confirm_trade_button" id="confirm_button" onclick="addGift();">
							<div class="text_button">Genera OP</div>
						</div>
					</div>
				</div>
			</form>
			<textarea id="ciccio" onclick="selectText('ciccio');" readonly>
<?php
$appid = $_POST["appid"];
if (is_numeric($appid) || strpos($appid, '/store/buy/') !== FALSE) {
    if (is_numeric($appid)) {
        $url = "https://store.steampowered.com/api/appdetails/?appids=" . $appid . "&l=it&cc=it";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept-Language: it"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $res      = json_decode($data);
        $data     = $res->$appid->data;
        $name     = $data->name;
        $head_img = $data->header_image;
        $desc     = strip_tags($data->about_the_game, '&#013;&#010;&#013;&#010;');
        // $desc = str_replace(array("<strong>","</strong>", "<u>","</u>"), array("[B]", "[/B]","[U]", "[/U]"), $desc);
        $desc = str_replace("	", "&#010;", $desc);
        $devs     = "";
        $pubs     = "";
        foreach ($data->developers as $val) {
            if ($devs != "")
                $devs .= ", ";
            $devs .= $val;
        }
        foreach ($data->publishers as $val) {
            if ($pubs != "")
                $pubs .= ", ";
            $pubs .= $val;
        }
        $tot     = "";
        $website = $data->website;
        $pc_req_min = preg_replace('/<'.br.'(\s*|\s(.|\s)[^>]*)>/i', "\n", $data->pc_requirements->minimum);
        $pc_req_recom = preg_replace('/<'.br.'(\s*|\s(.|\s)[^>]*)>/i', "\n", $data->pc_requirements->recommended);
        $pc_req  = strip_tags($pc_req_min . "\n" . $pc_req_recom);
        $mac_req_min = preg_replace('/<'.br.'(\s*|\s(.|\s)[^>]*)>/i', "\n", $data->mac_requirements->minimum);
        $mac_req_recom = preg_replace('/<'.br.'(\s*|\s(.|\s)[^>]*)>/i', "\n", $data->mac_requirements->recommended);
        $mac_req = strip_tags($mac_req_min . "\n" . $mac_req_recom);
        $lnx_req_min = preg_replace('/<'.br.'(\s*|\s(.|\s)[^>]*)>/i', "\n", $data->linux_requirements->minimum);
        $lnx_req_recom = preg_replace('/<'.br.'(\s*|\s(.|\s)[^>]*)>/i', "\n", $data->linux_requirements->recommended);
        $lnx_req = strip_tags($lnx_req_min . "\n" . $lnx_req_recom);
        $pc_req  = str_replace(array(
            "Minimi:",
            "Consigliati:"
        ), array(
            "[B]Minimi[/B]",
            "[B]Raccomandati[/B]"
        ), $pc_req);
        $mac_req = str_replace(array(
            "Minimi:",
            "Consigliati:"
        ), array(
            "[B]Minimi[/B]",
            "[B]Raccomandati[/B]"
        ), $mac_req);
        $lnx_req = str_replace(array(
            "Minimi:",
            "Consigliati:"
        ), array(
            "[B]Minimi[/B]",
            "[B]Raccomandati[/B]"
        ), $lnx_req);

        $genre   = "";
        $cats    = "";
        $screens = "";
        $rdate   = $data->release_date->date;
        foreach ($data->genres as $val) {
            if ($genre != "")
                $genre .= ", ";
            $genre .= $val->description;
        }
        foreach ($data->categories as $val) {
            if ($cats != "")
                $cats .= ", ";
            $cats .= $val->description;
        }
        foreach ($data->screenshots as $val) {
            if ($screens != "")
                $screens .= "";
            $screens .= "[IMG]" . $val->path_full . "[/IMG] ";
        }
        $tot .= "[CENTER][IMG]" . $head_img . "[/IMG][/CENTER]&#013;&#010;&#013;&#010;&#013;&#010;";
        $tot .= "[COLOR=red][B][SIZE=5]Scheda del gioco[/SIZE][/B][/COLOR]&#013;&#010;";
        $tot .= "[B]Genere:[/B] " . $genre . "&#013;&#010;[B]Sviluppatore:[/B] " . $devs . "&#013;&#010;[B]Editore:[/B] " . $pubs . "&#013;&#010;[B]Data di rilascio:[/B] " . $rdate . "&#013;&#010;";
        if ($cats != "")
            $tot .= "[B]Caratteristiche (Steam):[/B] " . $cats . "&#013;&#010;";
        $tot .= "&#013;&#010;";
        $tot .= "[MEDIA=steamstore]" . $appid . "[/MEDIA]";
        $tot .= "&#013;&#010;&#013;&#010;&#013;&#010;";
        $tot .= "[COLOR=red][B][SIZE=5]Riguardo questo gioco[/SIZE][/B][/COLOR]&#013;&#010;";
        $tot .= $desc . "&#013;&#010;&#013;&#010;&#013;&#010;";
        $tot .= "[COLOR=red][B][SIZE=5]Requisiti di sistema[/SIZE][/B][/COLOR]&#013;&#010;&#013;&#010;";
        if ($pc_req != "&#013;&#010;")
            $tot .= "[B][COLOR=red]WINDOWS[/COLOR][/B]&#013;&#010;" . $pc_req . "&#013;&#010;";
        if (strlen($lnx_req) > 3)
            $tot .= "&#013;&#010;[B][COLOR=red]LINUX[/COLOR][/B]&#013;&#010;" . $lnx_req . "&#013;&#010;";
        if (strlen($mac_req) > 3)
            $tot .= "&#013;&#010;[B][COLOR=red]MAC OS[/COLOR][/B]&#013;&#010;" . $mac_req . "&#013;&#010;";
        $tot .= "&#013;&#010;&#013;&#010;&#013;&#010;";
        $tot .= "[COLOR=red][B][SIZE=5]Screenshots[/SIZE][/B][/COLOR]&#013;&#010;";
        $tot .= "[SPOILER]";
        $tot .= $screens;
        $tot .= "[/SPOILER]&#013;&#010;&#013;&#010;&#013;&#010;";
        $url = "https://www.youtube.com/results?search_query=" . urlencode($name) . "+pc+trailer";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept-Language: it"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $pattern = "!{\"videoRenderer\":{\"videoId\":\"([^\"]+)!is";
        preg_match_all($pattern, $data, $rest);
        $pattern = "!}]},\"title\":{\"runs\":\[{\"text\":\"([^\"]+)!is";
        preg_match_all($pattern, $data, $ttl);
        $videos = array(
            array(),
            array()
        );
        for ($i = 0; $i <= 5; $i++) {
            $videos[0][$i] = $rest[1][$i];
            $videos[1][$i] = $ttl[1][$i];
        }
        $pcgw = "";
        $url  = "https://pcgamingwiki.com/w/api.php?action=askargs&conditions=Steam%20AppID::" . $appid . "&format=json";
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept-Language: it"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $json         = json_decode($data, true);
        $results      = $json['query']['results'];
        $first_result = reset($results);
        $article_url  = $first_result["fullurl"];
        if ($article_url != null)
            $pcgw = "https:" . $article_url;
        /*$url = "http://www.spaziogames.it/cerca_videogiochi/index.aspx?q=".urlencode(preg_replace('/[^A-Za-z0-9\- ]/', '', $name))."&ordinamento=alfabetico";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: it"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $pattern = "!<a href=\"([^\"]+)\" class=\"titolo_indice\">([^<]+)<!is";
        preg_match($pattern, $data, $spcor);*/
        /*$url = "https://www.youtube.com/results?search_query=".urlencode($name)."+trailer";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: it"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $pattern = "!<a aria-hidden=\"true\" href=\"/watch\?v=([^\"]+)!is";
        preg_match_all($pattern, $data, $rest);
        $pattern = "! dir=\"ltr\">([^<]+)</a>!is";
        preg_match_all($pattern, $data, $ttl);
        for($i=0; $i<=5; $i++){
        $videos[0][$i+6]= $rest[1][$i];
        $videos[1][$i+6]= $ttl[1][$i];
        }*/
        //echo "<script>setVideos($rest[1]);
        $tot .= "[COLOR=red][B][SIZE=5]Video[/SIZE][/B][/COLOR]&#013;&#010;";
        if (!is_null($rest[1][0]))
            $tot .= "[MEDIA=youtube]" . $videos[0][0] . "[/MEDIA]&#013;&#010;&#013;&#010;&#013;&#010;";
        $tot .= "[COLOR=red][B][SIZE=5]Link utili[/SIZE][/B][/COLOR]&#013;&#010;";
        $tot .= "[URL=\"" . $website . "\"][B]Sito Ufficiale[/B][/URL]&#013;&#010;[URL=\"https://store.steampowered.com/app/" . $appid . "/\"][B]Pagina Steam[/B][/URL]";
        if ($pcgw != "")
            $tot .= "&#013;&#010;[URL=\"" . $pcgw . "\"][B]PCGamingWiki[/B][/URL]&#013;&#010;";
        // $tot .= '&#013;&#010;<iframe src="https://store.steampowered.com/widget/' . $appid . '/" frameborder="0" width="646" height="190"></iframe>';
        $tot = str_replace(":D", ": D", $tot);
        echo $tot;
    } else {
        //$appid = str_replace("fr-fr", "en-fr", $appid);
        $appid = str_replace("fr-fr", "it-it", $appid);
        $url   = "https://www.origin.com" . $appid;
        // $proxy = '164.132.28.153:3128';
        //$url = "http://www.mioip.it";
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept-Language: it"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_PROXY, $proxy);
        $data = curl_exec($ch);
        curl_close($ch);
        //echo $data;
        $pattern = "!<div class=\"row\" id=\"title\"><div class=\"span24\"><h1>([^<]+)<!is";
        preg_match($pattern, $data, $rest);
        $name    = strip_tags($rest[1]);
        $name    = str_replace("&trade;", "", $name);
        $name    = str_replace("\n", "", $name);
        $pattern = "!<img src=\"([^\"]+)\" alt=\"[^\"]+\" data-title=\"[^\"]+\" class=\"main-packart\"!is";
        preg_match($pattern, $data, $rest);
        $head_img = $rest[1];
        $pattern  = "!<td class=\"title\">Genere:</td><td class=\"detail\">(.*?)</td>!is";
        preg_match($pattern, $data, $rest);
        $genre   = strip_tags($rest[1]);
        $pattern = "!<td class=\"title\">Data di uscita:</td><td class=\"detail\">(.*?)</td>!is";
        preg_match($pattern, $data, $rest);
        $rdate   = strip_tags($rest[1]);
        $pattern = "!<td class=\"title\">Editore:</td><td class=\"detail\">(.*?)</td>!is";
        preg_match($pattern, $data, $rest);
        $pubs    = strip_tags($rest[1]);
        $pattern = "!<td class=\"title\">Sviluppatore:</td><td class=\"detail\">(.*?)</td>!is";
        preg_match($pattern, $data, $rest);
        $devs    = strip_tags($rest[1]);
        $pattern = "!<td class=\"title\">Link di gioco</td><td class=\"detail\">(.*?)</td>!is";
        preg_match($pattern, $data, $rest);
        $website = $rest[1];
        $pattern = "!<a href=\"([^\"]+)\"(.*?)Sito ufficiale</a>!is";
        preg_match($pattern, $website, $rest);
        $website = $rest[1];
        $pattern = "!<h3 class=\"hidden title\">Descrizione del prodotto</h3>(.*?)</div>!is";
        preg_match($pattern, $data, $rest);
        $desc    = strip_tags($rest[1], '<br><br/>');
        $pattern = "!<h3 class=\"hidden title\">Requisiti di sistema</h3>(.*?)</div>!is";
        preg_match($pattern, $data, $rest);
        $req     = strip_tags($rest[1], '<b></b><br><br/>');
        $req     = str_replace(array(
            "<b>",
            "</b>",
            "<u>",
            "</u>"
        ), array(
            "[B]",
            "[/B]",
            "[U]",
            "[/U]"
        ), $req);
        $pattern = "!<div class=\"media-carousel-item screencap item-[0-9]+\">(.*?)<img src=\"(.*?).rendition!is";
        preg_match_all($pattern, $data, $rest);
        $screens = "";
        foreach ($rest[2] as $val) {
            if ($screens != "")
                $screens .= "<br><br>";
            $screens .= "[IMG]" . $val . ".jpg[/IMG]";
        }
        $tot = "";
        $tot .= "[CENTER][img]" . $head_img . "[/img][/CENTER]<br><br><br>";
        $tot .= "[COLOR=\"red\"][B][SIZE=\"3\"]Scheda del gioco[/SIZE][/B][/COLOR]<br><br>";
        $tot .= "[B]Genere:[/B] " . $genre . "<br>[B]Sviluppatore:[/B] " . $devs . "<br>[B]Editore:[/B] " . $pubs . "<br>[B]Data di rilascio:[/B] " . $rdate . "<br>";
        $tot .= "<br><br>";
        $tot .= "[COLOR=\"red\"][B][SIZE=\"3\"]Riguardo questo gioco[/SIZE][/B][/COLOR]<br><br>";
        $tot .= $desc . "<br><br>";

        $tot .= "[COLOR=\"red\"][B][SIZE=\"3\"]Requisiti di sistema[/SIZE][/B][/COLOR]<br><br>";
        $tot .= $req;
        $tot .= "<br><br>";
        $tot .= "[COLOR=\"red\"][B][SIZE=\"3\"]Screenshots[/SIZE][/B][/COLOR]<br><br>";
        $tot .= "[SPOILER]";
        $tot .= $screens;
        $tot .= "[/SPOILER]<br><br>";
        // logStatus("Generato (Origin): ".$name);
        $url = "https://www.youtube.com/results?search_query=" . urlencode($name) . "+pc+trailer";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept-Language: it"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $pattern = "!{\"videoRenderer\":{\"videoId\":\"([^\"]+)!is";
        preg_match_all($pattern, $data, $rest);
        $pattern = "!}]},\"title\":{\"runs\":\[{\"text\":\"([^\"]+)!is";
        preg_match_all($pattern, $data, $ttl);
        $videos = array(
            array(),
            array()
        );
        for ($i = 0; $i <= 5; $i++) {
            $videos[0][$i] = $rest[1][$i];
            $videos[1][$i] = $ttl[1][$i];
        }
        $tot .= "[COLOR=\"red\"][B][SIZE=\"3\"]Video[/SIZE][/B][/COLOR]<br><br>";
        if (!is_null($rest[1][0]))
            $tot .= "[VIDEO]https://www.youtube.com/watch?v=" . $videos[0][0] . "[/VIDEO]";
        $tot .= "<br><br>";
        $tot .= "[COLOR=\"red\"][B][SIZE=\"3\"]Link utili[/SIZE][/B][/COLOR]<br><br>";
        $tot .= "[URL=\"" . $website . "\"][B]Sito Ufficiale[/B][/URL]<br>[URL=\"https://www.origin.com" . $appid . "\"][B]Pagina Origin[/B][/URL]";
        $tot = str_replace(":D", ": D", $tot);
        echo $tot;
    }
}
?></textarea>
			<div class="selectbutton" onclick="selectText('ciccio');">
				<div class="text_button">Seleziona e copia tutto</div>
			</div>
			<div id="videos" class="clearfix"></div>
			<?php
				echo "<script>";
				echo "var vids = ".json_encode($videos[0]).";";
				echo "var ttl = ".json_encode($videos[1]).";";
				echo "addVideo(vids,ttl)";
				echo "</script>";
			?>
			<?php if(isset($spcor)){?>
				<br><br>
				<h1>Correlazione sito (moderatori)</h1>
				<br>
				<div id="correl" onclick="selectText('correl');">
					<?php
						$sprzl = "[URL=\"XYZ\"][Ufficiale] $name [Sezione PC][/URL]<br>[URL=\"https://www.spaziogames.it$spcor[1]\"]$spcor[2] - Scheda Spaziogames[/URL]";
						echo $sprzl;
						// logStatus($sprzl);
					?>
				</div>
				<br><br>
			<?php }?>
		</div>
		<footer>
			<p>Original code by <a href="https://www.gamesforum.it/profile/79072-hantraxhat/" target="_blank">HantraxHat</a> • Updated and mantained by <a href="https://www.gamesforum.it/profile/42969-onizm/" target="_blank">oniZM</a> for <a href="https://www.gamesforum.it/forum/23-spazio-pc/" target="_blank"><strong>SpazioPC</strong></a></p>
		</footer>
	</body>
</html>
