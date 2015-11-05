<?php
function check($str,$pass){
	$plik=fopen("data/conf/users.conf","r");
		while($linia=fgets($plik)){
			if($linia[0]=='#'){
				continue;
			}
			$id=explode('||',$linia)[0];
			$l=explode('||',$linia)[1];
			$p=explode('||',$linia)[2];		
			if($str==$l and md5("S417".$id."".substr($l,0,3)."".$pass)==$p){
				$ID=$id;
				fclose($plik);
				return true;
			}
		}
	fclose($plik);
	return false;
}

function check_login($login){
	$excl=["~","`","!","@","#","$","%","^","&","*","(",")","+","=","{","}",":",";","'",'"',"<",",",">",".","/","?"];
	for($i=0;$i<strlen($login);$i++){
		if(in_array($login[$i],$excl)){
			return false;
		}
	}
	return true;
}

function check_cookie($name,$sid,$auth){
	$plik=fopen("data/conf/auth.var","r");
	while($line=fgets($plik)){
		$l=explode("||",$line);
		if($l[0]==$name && $l[1]==$sid && $l[2]==$auth){
			return true;
		}
	}
	fclose($plik);
	return false;
}

function check_if_exists($str){
	$plik=fopen("data/conf/users.conf","r");
		while($linia=fgets($plik)){
			if($linia[0]=='#'){
				continue;
			}
			$l=split('[||]',$linia)[2];
			if($str==$l){
				echo "Rejestracja nie powiodła się! Istnieje już użytkownik o takiej nazwie!<br />";
				fclose($plik);
				return true;
			}
		}
	fclose($plik);
	return false;
}

function last_id(){
	$plik=fopen("data/conf/users.conf","r");
	while($linia=fgets($plik)){
			if($linia[0]=='#'){
				continue;
			}
			$id=explode('||',$linia)[0];
		}
	return $id;
}
?>