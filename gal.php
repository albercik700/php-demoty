<?php

function load_main($tab,$page){
	$tab_r = array_reverse($tab);
	if($page>(count($tab_r)/10)+1 || $page==0){
		$page=1;
	}
	if(count($tab_r)==1){
		$min=0;
	}else{
		$min=1;
	}
	if(count($tab_r)<11){
		$max=count($tab_r);
	}else{
		$max=(10*$page)+1;
		$min=$max-10;
		if($max>count($tab_r)){
			$max=count($tab_r);
		}
	}
	for($i=$min;$i<$max;$i++){
		echo "<div id=\"content\">		
			<img style=\"display:block; margin: 0 auto;\" src=\"data/$tab_r[$i].jpg\" />
			<br />";
		$tmp=fopen("data/".$tab_r[$i].".dsc","r");
		while($l_in_tmp=fgets($tmp)){
			$a=explode("::",$l_in_tmp);
			if($l_in_tmp[0]=='#'){
				continue;
			}else if($a[0]=='meta'){
				$m=explode("||",$a[1]);
				echo "<span style=\"font-size: 9px; color:#696969;\"><b>Data dodania: </b>$m[1]</span>
				<span style=\"font-size: 9px; float:right; margin-left: 20px; font-weight:bold;\"><a href=\"?abuse=".$tab_r[$i]."\" id=\"viol\">Zgłoś naruszenie</a></span><br />
				<span style=\"font-size: 9px; color:#696969;\"><b>Użytkownik: </b>$m[0]</span><br />";
			}else if($a[0]=='cats'){
				$c=explode("||",$a[1]);
				echo "<span style=\"font-size: 9px; color:#696969;\"><b>Kategorie: </b></span>"; 
				for($j=0;$j<count($c)-1;$j++){
					echo "<a href=\"?cat=$c[$j]\" id=\"cat\">$c[$j]</a> ";
				}
			}else if($a[0]=='comments'){
				continue;
			}else{
				$comm=explode("||",$l_in_tmp);
			}
		}
		fclose($tmp);
		$f="data/".$tab_r[$i].".dsc";
		$file=file($f);
		$count=count($file)-4;
		echo "<span style=\"font-size: 10px; float:right;\"><a href=\"?id=".$tab_r[$i]."\" id=\"cat\">Komentarze [<b>$count</b>]</a></span>
			</div><br /><hr /><br />";
	}
	if(isset($_GET['cat'])){
		if(count($tab_r)%10==0){
			echo "<center><span style=\"font-size: 10px; color:#696969;\"><b>Strona</b></span> ";
			for($k=1;$k<=(count($tab_r)/10);$k++){
				if($k==$page){
					echo "<a href=\"index.php?cat=".$_GET['cat']."&p=$k\" id=\"cat\"><b>".$k."</b></a> ";
				}else{
					echo "<a href=\"index.php?cat=".$_GET['cat']."&p=$k\" id=\"cat\">".$k."</a> ";
				}
			}
		}else{
			echo "<center><span style=\"font-size: 10px; color:#696969;\"><b>Strona</b></span> ";
			for($k=1;$k<=(count($tab_r)/10)+1;$k++){
				if($k==$page){
					echo "<a href=\"index.php?cat=".$_GET['cat']."&p=$k\" id=\"cat\"><b>".$k."</b></a> ";
				}else{
					echo "<a href=\"index.php?cat=".$_GET['cat']."&p=$k\" id=\"cat\">".$k."</a> ";
				}
			}
		}
	}else{
		if(count($tab_r)%10==0){
			echo "<center><span style=\"font-size: 10px; color:#696969;\"><b>Strona</b></span> ";
			for($k=1;$k<=(count($tab_r)/10);$k++){
				if($k==$page){
					echo "<a href=\"index.php?p=$k\" id=\"cat\"><b>".$k."</b></a> ";
				}else{
					echo "<a href=\"index.php?p=$k\" id=\"cat\">".$k."</a> ";
				}
			}
		}else{
			echo "<center><span style=\"font-size: 10px; color:#696969;\"><b>Strona</b></span> ";
			for($k=1;$k<=(count($tab_r)/10)+1;$k++){
				if($k==$page){
					echo "<a href=\"index.php?p=$k\" id=\"cat\"><b>".$k."</b></a> ";
				}else{
					echo "<a href=\"index.php?p=$k\" id=\"cat\">".$k."</a> ";
				}
			}
		}
	}
	echo "</center>";
}
function load($cat,$page){
	if($cat=='def'){
		$file = file_get_contents("data/gall.dat");
		$tab = explode(".jpg\n", $file);
		load_main($tab,$page);
	}else{
		$f="data/cats/".$cat.".dat";
		if(file_exists($f) && !empty($f)){
			$file = file_get_contents($f);
			$tab = explode(".jpg\n", $file);
			load_main($tab,$page);
		}else{
			echo "<br />Nieprawidłowa nazwa kategorii.";
		}
	}
}

function load_id($id){
		$f=".\\data\\".$id.".dsc";
		if(file_exists($f)){
			echo "<div id=\"content\">		
			<img style=\"display:block; margin: 0 auto;\" src=\"data/$id.jpg\" />
			<br />";
			$tmp=fopen($f,"r");
			$file=file($f);
			$count=count($file)-4;
			while($l_in_tmp=fgets($tmp)){
				$a=explode("::",$l_in_tmp);
				if($l_in_tmp[0]=='#'){
					continue;
				}else if($a[0]=='meta'){
					$m=explode("||",$a[1]);
					echo "<span style=\"font-size: 9px; color:#696969;\"><b>Data dodania: </b>$m[1]</span>
				<span style=\"font-size: 9px; float:right; margin-left: 20px; font-weight:bold;\"><a href=\"?abuse=$id\" id=\"viol\">Zgłoś naruszenie</a></span><br />
				<span style=\"font-size: 9px; color:#696969;\"><b>Użytkownik: </b>$m[0]</span><br />";
				}else if($a[0]=='cats'){
					$c=explode("||",$a[1]);
					echo "<span style=\"font-size: 9px; color:#696969;\"><b>Kategorie: </b></span>"; 
					for($i=0;$i<count($c);$i++){
						echo "<a href=\"?cat=$c[$i]\" id=\"cat\">$c[$i]</a> ";
					}
				}else if($a[0]=='comments'){
					echo "<span style=\"font-size: 10px; float:right;\"><a href=\"?id=$id\" id=\"cat\">Komentarze [<b>$count</b>]</a></span>
						<br />";
					echo "<span style=\"margin-left: 20px;\"><h2>Komentarze<h2></span>";	
					echo"</div><hr /><br />";
				}else{
					$comm=explode("||",$l_in_tmp);
					echo "<div id=\"cmnt\"><span style=\"color: #dfdfdf;\">$comm[1]</span>  <b>$comm[0]</b> <br /><span style=\"margin-left:20px; margin-top: 10px; word-wrap:break-word;\">$comm[2]</span></div><br />";
				}
			}
			fclose($tmp);
			if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
				&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
				echo "<div id=\"cmnt\"><b>Dodaj Komentarz</b><br/>
				<form method=\"post\" action=\"?id=$id\">
				<textarea name=\"comment\" rows=\"4\" cols=\"88\"></textarea><br />
				<input type=\"submit\" value=\"Wyślij\"></div>";
			}
		}else{
			echo "<br />Nieprawidłowy identyfikator obrazu";
		}
}

function load_abuse($id){
		$f=".\\data\\".$id.".dsc";
		if(file_exists($f)){
			echo "<div id=\"content\">		
			<img style=\"display:block; margin: 0 auto;\" src=\"data/$id.jpg\" />
			<br />";
			$tmp=fopen($f,"r");
			$file=file($f);
			$count=count($file)-4;
			while($l_in_tmp=fgets($tmp)){
				$a=explode("::",$l_in_tmp);
				if($l_in_tmp[0]=='#'){
					continue;
				}else if($a[0]=='meta'){
					$m=explode("||",$a[1]);
					echo "<span style=\"font-size: 9px; color:#696969;\"><b>Data dodania: </b>$m[1]</span>
				<span style=\"font-size: 9px; float:right; margin-left: 20px; font-weight:bold;\"><a href=\"?abuse=$id\" id=\"viol\">Zgłoś naruszenie</a></span><br />
				<span style=\"font-size: 9px; color:#696969;\"><b>Użytkownik: </b>$m[0]</span><br />";
				}else if($a[0]=='cats'){
					$c=explode("||",$a[1]);
					echo "<span style=\"font-size: 9px; color:#696969;\"><b>Kategorie: </b></span>"; 
					for($i=0;$i<count($c);$i++){
						echo "<a href=\"?cat=$c[$i]\" id=\"cat\">$c[$i]</a> ";
					}
				}else if($a[0]=='comments'){
					echo "<span style=\"font-size: 10px; float:right;\"><a href=\"?id=$id\" id=\"cat\">Komentarze [<b>$count</b>]</a></span>
						<br />";
					echo "<span style=\"margin-left: 20px;\"><h2>Komentarze<h2></span>";	
					echo"</div><hr /><br />";
				}else{
					$comm=explode("||",$l_in_tmp);
					echo "<div id=\"cmnt\"><span style=\"color: #dfdfdf;\">$comm[1]</span>  <b>$comm[0]</b> <br /><span style=\"margin-left:20px; margin-top: 10px; word-wrap:break-word;\">$comm[2]</span></div><br />";
				}
			}
			fclose($tmp);
			if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
				&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
				echo "<div id=\"cmnt\"><b>Wyślij zgłoszenie</b><br/>
				<form method=\"post\" action=\"?abuse=$id\">
				<textarea name=\"comment\" rows=\"4\" cols=\"88\"></textarea><br />
				<input type=\"submit\" value=\"Wyślij\"></div>";
			}
		}else{
			echo "<br />Nieprawidłowy identyfikator obrazu";
		}
}

function add_abuse($id,$comment){
	mail("hoperoski@gmail.com","Zgłoszenie zdjęcia $id",$comment,"FROM: prezydent@prezydent.gov.pl");
}

function add_comment($id,$comment){
	$f=".\\data\\".$id.".dsc";
	if(file_exists($f)){
		$tmp=fopen($f,"a");
	}else{
		echo "Brak obrazu";
		die();
	}
	if(fwrite($tmp,htmlspecialchars($_COOKIE['MyCookie'])."||".date("d-m-Y G:i:s")."||".htmlspecialchars($_POST['comment'])."||\n"));
	fclose($tmp);
}

if(isset($_GET['cat']) && isset($_GET['p']) && is_numeric($_GET['p'])){
	$cat=$_GET['cat'];
	$page=$_GET['p'];
	load($cat,$page);
}else if(isset($_GET['cat'])){
	$cat=$_GET['cat'];
	$page=1;
	load($cat,$page);
}else if(isset($_GET['p']) && is_numeric($_GET['p'])){
	$cat='def';
	$page=$_GET['p'];
	load($cat,$page);
}else if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth']) && isset($_GET['id']) && isset($_POST['comment'])){
	$id=$_GET['id'];
	add_comment($id,$_POST['comment']);
	load_id($id);
}else if(isset($_GET['id'])){
	$id=$_GET['id'];
	load_id($id);
}else if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth']) && isset($_GET['abuse']) && isset($_POST['comment'])){
	$abuse=$_GET['abuse'];
	add_abuse($abuse,$_POST['comment']);
	$ref=$_SERVER['HTTP_REFERER'];
	header("Location: ".$ref);
}else if(isset($_GET['abuse'])){
	$abuse=$_GET['abuse'];
	load_abuse($abuse);
}else{
	$cat='def';
	$page=1;
	load($cat,$page);
}
?>