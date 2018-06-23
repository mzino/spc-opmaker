function getSuggest(){
	var name = document.getElementById("g_name");
	var sr_list = document.getElementById("sr_list");
	clearTimeout(timeout);
	timeout = setTimeout(function(){getSuggestNow(name.value, "US")},500);
}
var timeout, waiting=false;
function getSuggestNow(name, cc){
	var sr_list = document.getElementById("sr_list");
	var input_name = document.getElementById("g_name");
	if(name!="" && !waiting){
		selected_element="";
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				sr_list.innerHTML="";
				result=xmlhttp.responseText;
				var myArray;
				var pattern = new RegExp("<a class=\"match ds_collapse_flag \"  data-ds-([a-z]+)id=\"([0-9]+)\"[^>]*\><div class=\"match_name\">([^>]*)</div><div class=\"match_img\"><img src=\"([^\"]*)\">", "g");
				//var pattern = new RegExp("<a class=\"match\" href=\"http:\/\/store.steampowered.com\/([a-z]+)\/([0-9]+)[^>]*\><div class=\"match_name\">([^>]*)</div><div class=\"match_img\"><img src=\"([^\"]*)\">", "g");
				var i=0;
				var title="";
				var first;
				while ((myArray = pattern.exec(result)) !== null){
					if(myArray[1] == "app"){
						option = document.createElement('option');
						option.innerHTML = myArray[3];
						option.value = myArray[2];
						title = myArray[3];
						z = document.createAttribute('onclick');
						z.value = "select_game(this);";
						option.setAttributeNode(z);
						z = document.createAttribute('onmouseover');
						// z.value = "show_overlay(this);";
						option.setAttributeNode(z);
						z = document.createAttribute('onmouseout');
						// z.value = "hide_overlay(this);";
						option.setAttributeNode(z);
						z = document.createAttribute('link_img');
						z.value = myArray[4];
						option.setAttributeNode(z);
						z = document.createAttribute('type_pk');
						z.value = myArray[1];
						option.setAttributeNode(z);
						sr_list.appendChild(option);
						i++;
					}
				}
				sr_list.size = i;
				if(i==1){
					document.getElementById("appid").value = option.value;
					sr_list.value = option.value;
					sr_list.size = 2;
					input_name.value = title;
				}
			}
			waiting = false;
			input_name.style.backgroundColor="white";
		}
		xmlhttp.open("GET","getSuggest.php?term="+name+"&cc="+cc,true);
		xmlhttp.send();
		waiting = true;
	}
	else if(name=="" && !waiting){
		sr_list.innerHTML="";
		sr_list.size = 1;
	}
}
// function show_overlay(element){
// 	var over = document.getElementById("overlay_game");
// 	var x = getPos(document.getElementById("sr_list"));
// 	over.innerHTML = "<div id=\"title\">" + element.innerHTML + "</div><div><img id=\"titleimg\" src=\""+element.getAttribute("link_img")+"\"></div>";
	// over.style.top =  x.y+"px";
	// over.style.left = (GetWidth()/2+150) + "px";
	// over.style.display = "block";
// }
// function hide_overlay(element){
// 	var over = document.getElementById("overlay_game");
// 	if(selected_element==""){
// 		over.style.display = "none";
// 	}
// 	else{
// 		show_overlay(selected_element);
// 	}
// }
function GetWidth(){
	var x = 0;
	if (self.innerHeight)
		x = self.innerWidth;
	else if (document.documentElement && document.documentElement.clientHeight)
		x = document.documentElement.clientWidth;
	else if (document.body)
		x = document.body.clientWidth;
	return x;
}

function getPos(obj) {
	var pos = {'x':0,'y':0};
	if(obj.offsetParent) {
		while(1) {
			pos.x += obj.offsetLeft;
			pos.y += obj.offsetTop;
			if(!obj.offsetParent) {
				break;
			}
			obj = obj.offsetParent;
		}
	} else if(obj.x) {
		pos.x += obj.x;
		pos.y += obj.y;
	}
	return pos;
}
function checksel_game(index){
	var sr_list = document.getElementById("sr_list");
	select_game(sr_list.options[index]);
}
function select_game(sel_option){
	var over = document.getElementById("overlay_game");
	var x = getPos(document.getElementById("sr_list"));
	over.innerHTML = "<div id=\"title\">" + sel_option.innerHTML + "</div><div><img id=\"titleimg\" src=\""+sel_option.getAttribute("link_img")+"\"></div>";
	document.getElementById("appid").value = sel_option.value;
	document.getElementById("link_img").value =  sel_option.getAttribute("link_img");
	if(sel_option.getAttribute("origin") == 1){
		var name = document.getElementById("g_name_or");
		document.getElementById("g_name").value = "";
	}
	else{
		var name = document.getElementById("g_name");
		document.getElementById("g_name_or").value = "";
	}
	name.value = sel_option.innerHTML;
	selected_element = sel_option;
}
function addGift(){
	document.getElementById("ciccio").innerHTML = "";
	document.getElementById("videos").innerHTML = "";
	var a = document.getElementById("appid").value;
	var b = document.getElementById("link_img").value;
	if(a!="" && b!= "")
		document.forms["form_gift"].submit();
}
oldElement = "";
function addVideo(vids, ttl){
	document.getElementById("videos").innerHTML = "";
	tot ="";
	if(vids)
		for(i=0; i<=5; i++){
			if(i==0){
				tot+= 
				tot+= "<h2>Cambia video</h2><br><div id =\"frs\" class = \"box_vd active\"";
			} else
				tot+= "<div class = \"box_vd\"";
			tot+= " onclick = \"selectPK(this, '"+vids[i]+"');\"><img style=\"width: 200px;\" src=\"https://i.ytimg.com/vi/"+vids[i]+"/mqdefault.jpg\"><br>"+ttl[i]+"</div>";
		}
	document.getElementById("videos").innerHTML = tot;
	oldElement = document.getElementById("frs");
}
function selectPK(element, vids){
	if(oldElement != ""){
		oldElement.className = "box_vd";
	}
	element.className = "box_vd active";
	oldElement = element;
	setVideo(vids);
}
function setVideo(vids){
	document.getElementById("ciccio").innerHTML = document.getElementById("ciccio").innerHTML.replace(/watch\?v=[^\[]+/gi, "watch\?v="+vids);
}
function getSuggestOrigin(){
	var name = document.getElementById("g_name_or");
	var sr_list = document.getElementById("sr_list");
	clearTimeout(timeout);
	timeout = setTimeout(function(){getSuggestNowOrigin(name.value, "US")},500);
}
function getSuggestNowOrigin(name, cc){
	var sr_list = document.getElementById("sr_list");
	var input_name = document.getElementById("g_name_or");
	if(name!="" && !waiting){
		selected_element="";
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				sr_list.innerHTML="";
				result=xmlhttp.responseText;
				var myArray;
				var pattern = new RegExp("data-title=\"([^\"]*)\"", "g");
				var p2 = new RegExp("game-link\" href=\"([^\"]*)\"", "g");
				var p3 = new RegExp("search-packart\" src=\"([^\"]*)\"", "g");
				//var pattern = new RegExp("<a class=\"match ds_collapse_flag \"  data-ds-([a-z]+)id=\"([0-9]+)\"[^>]*\><div class=\"match_name\">([^>]*)</div><div class=\"match_img\"><img src=\"([^\"]*)\">", "g");
				//var pattern = new RegExp("<a class=\"match\" href=\"http:\/\/store.steampowered.com\/([a-z]+)\/([0-9]+)[^>]*\><div class=\"match_name\">([^>]*)</div><div class=\"match_img\"><img src=\"([^\"]*)\">", "g");
				var i=0;
				var title="";
				var first;
				while ((myArray = pattern.exec(result)) !== null){
					p2ar = p2.exec(result);
					p3ar =  p3.exec(result);
					option = document.createElement('option');
					option.innerHTML = myArray[1];
					option.value = p2ar[1];
					title = myArray[1];
					z = document.createAttribute('onclick');
					z.value = "select_game(this);";
					option.setAttributeNode(z);
					z = document.createAttribute('onmouseover');
					// z.value = "show_overlay(this);";
					option.setAttributeNode(z);
					z = document.createAttribute('onmouseout');
					// z.value = "hide_overlay(this);";
					option.setAttributeNode(z);
					z = document.createAttribute('origin');
					z.value = "1";
					option.setAttributeNode(z);
					z = document.createAttribute('link_img');
					z.value = p3ar[1];
					option.setAttributeNode(z);
					sr_list.appendChild(option);
					i++;
				}
				sr_list.size = i;
				if(i==1){
					document.getElementById("appid").value = option.value;
					sr_list.value = option.value;
					sr_list.size = 2;
					input_name.value = title;
				}
			}
			waiting = false;
			input_name.style.backgroundColor="white";
		}
		xmlhttp.open("GET","getSuggestOrigin.php?term="+name+"&cc="+cc,true);
		xmlhttp.send();
		waiting = true;
	}
	else if(name=="" && !waiting){
		sr_list.innerHTML="";
		sr_list.size = 1;
	}
}
