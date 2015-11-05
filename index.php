<?php
include("fs.php");
if(isset($_GET['act'])){
	$act=$_GET['act'];
	if($act=='logout' && isset($_COOKIE['MyCookie']) && isset($_COOKIE['Auth'])){
		$auth=fopen("data/conf/auth.var","r");
		$tmp='';
		while($linia=fgets($auth)){
			$data=explode("||",$linia);
			if(($data[1]!=$_COOKIE['PHPSESSID']) && ($data[2]!=$_COOKIE['Auth'])){
				$tmp=$tmp.$linia;
			}
		}
		setcookie('PHPSESSID','',time()-3600);
		setcookie('MyCookie','',time()-3600);
		setcookie('Auth','',time()-3600);
		echo $tmp;
		fclose($auth);
		$auth=fopen("data/conf/auth.var","w");
		fwrite($auth,$tmp);
		fclose($auth);
		$ref=$_SERVER['HTTP_REFERER'];
		header("Location: ".$ref);
	}
}
?>
<html>
	<head>
		<title>Sample</title>
		<link href="style/main.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
	<noscript>
	<br><br><br>
		<center><b>Ta strona wymaga włączonego JavaScript!</b></center>
		<style>
		div{
			visibility:hidden;
		} 
		</style>
	</noscript>
	<div id="header">
		<div id="logo">
			<a href="index.php" id="logo">d3motywatory.pl</a>
		</div>
		<?php
		if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
			&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
			echo "<div id=\"reg\">
			<span style=\"word-spacing: 5px;\"><a id=\"top\" href=\"javascript:void(0)\" onClick=\"document.getElementById('cat_show').style.display='block';document.getElementById('log_show').style.display='none'
			document.getElementById('up_show').style.display='none'\" ondblClick=\"document.getElementById('cat_show').style.display='none'\">Dodaj kategorię</a></span>
			<span style=\"word-spacing: 5px;\"><a id=\"top\" href=\"javascript:void(0)\" onClick=\"document.getElementById('up_show').style.display='block';document.getElementById('log_show').style.display='none'
			document.getElementById('cat_show').style.display='none'\" ondblClick=\"document.getElementById('up_show').style.display='none'\">Dodaj obraz</a></span>
			<span style=\"word-spacing: 5px;\"><a id=\"top\" href=\"javascript:void(0)\" onClick=\"document.getElementById('log_show').style.display='block';document.getElementById('up_show').style.display='none'
			document.getElementById('cat_show').style.display='none'\" ondblClick=\"document.getElementById('log_show').style.display='none'\">Profil</a></span>
			<span style=\"word-spacing: 5px;\"><a id=\"top\" href=\"index.php?act=logout\">Wyloguj <i>[".$_COOKIE['MyCookie']."]</i></a></span>		
			</div>";	
		}else{
			echo "<div id=\"reg\">
			<span style=\"word-spacing: 5px;\"><a id=\"top\" href=\"javascript:void(0)\" onClick=\"document.getElementById('log_show').style.display='block';document.getElementById('reg_show').style.display='none'\" ondblClick=\"document.getElementById('log_show').style.display='none'\">Logowanie</a></span>
			<span style=\"word-spacing: 5px;\"><a id=\"top\" href=\"javascript:void(0)\" onClick=\"document.getElementById('reg_show').style.display='block';document.getElementById('log_show').style.display='none'\" ondblClick=\"document.getElementById('reg_show').style.display='none'\">Rejestracja</a></span>	
			</div>";
		}
		?>	
	</div>
	<div id="reg_fix">
		<div id="cat_show">
			<?php
			if(isset($_POST['cat']) && isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
				&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth']) && check_login($cat)){
					$cat=$_POST['cat'];
					if(!file_exists("data/cats/$cat.dat")){
						$plik=fopen("data/cats/$cat.dat","w");
						fclose($plik);
					}
					$ref=$_SERVER['HTTP_REFERER'];
					header("Location: ".$ref);
				}else{
					echo "<form method=\"post\" action=\"index.php\">
					Nazwa: <input style=\"margin-left: 12px; width: 120px;\" type=\"text\" name=\"cat\"/><br />
					<input type=\"submit\" name=\"reg\" value=\"Dodaj kategorię\">
					</form>";
				}
			?>
		</div>
		<div id="up_show">
			<?php 
			if(isset($_FILES['plik']) && isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
				&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
				if(is_uploaded_file($_FILES['plik']['tmp_name']) and $_FILES['plik']['type'] =='image/jpeg' and 
				getimagesize($_FILES['plik']['tmp_name'])[0]==600){
					$md5=md5_file($_FILES['plik']['tmp_name']);
					move_uploaded_file($_FILES['plik']['tmp_name'],'data/'.$md5.'.jpg');
					$plik=fopen("data/".$md5.".dsc","w");
					fwrite($plik,"#nazwa_usera||data||md5.jpg\n");
					fwrite($plik,"meta::".$_COOKIE['MyCookie']."||".date("d-m-Y G:i:s")."||$md5.jpg||\n");
					fwrite($plik,"cats::");
					foreach($_POST['cat'] as $c){
						$tmp=fopen("data/cats/$c.dat","a");
						fwrite($tmp,$md5.".jpg\n");
						fclose($tmp);
						fwrite($plik,$c."||");
					}
					fwrite($plik,"\ncomments::\n");
					fclose($plik);
					$plik=fopen("data/gall.dat","a");
					fwrite($plik,$md5.".jpg\n");
					fclose($plik);	
				}
				$ref=$_SERVER['HTTP_REFERER'];
				header("Location: ".$ref);
			}else if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
				&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
				echo "<form action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">
				<input type=\"file\" name=\"plik\"/><br/>
				<select style=\"height: 120px; width: 200px;overflow-y:scroll;\" name=\"cat[]\" multiple=\"multiple\">";
				if($handle = opendir("data/cats")){
					while(false !=($wpis=readdir($handle))){
						if(empty($wpis)==false){
							echo "<option>".substr($wpis,0,strlen($wpis)-4)."</option>\n";
						}
					}
				}
				closedir($handle);
				echo "</select>\n<br/><input type=\"submit\" value=\"Wyślij\"/>
				<span style=\"font-size: 9px; color:#696969;\">Akceptuje jpg o szer. 600px</span>
				</form>";
			}else{
				
			}
			?>
		</div>
		<div id="log_show">
			<?php
			if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth']) && isset($_POST['old_password']) && isset($_POST['new_password'])){
			$plik=fopen("data/conf/users.conf","r");
			$tmp='';
			while($linia=fgets($plik)){
				if($linia[0]=='#'){
						continue;
				}
				$i=explode('||',$linia)[0];
				$l=explode('||',$linia)[1];
				$p=explode('||',$linia)[2];
				if($_COOKIE['MyCookie']==$l and md5("S417".$i."".substr($l,0,3)."".$_POST['old_password'])==$p){
					$tmp=$tmp.$i."||".$l."||".md5("S417".$i."".substr($l,0,3)."".$_POST['new_password'])."||\n";
				}else{
					$tmp=$tmp.$linia;
				}
			}
			fclose($plik);
			$plik=fopen("data/conf/users.conf","w");
			fwrite($plik,$tmp);
			fclose($plik);
			$ref=$_SERVER['HTTP_REFERER'];
			header("Location: ".$ref);
		}else if(isset($_POST['login']) && isset($_POST['password'])){
			$login=$_POST['login'];
			$pass=$_POST['password'];
			if(check($login,$pass)){
				session_destroy();
				session_start();
				session_regenerate_id();
				$_SESSION['user']=$login;
				$_SESSION['sid']=session_id();
				$authh=substr(md5($login."s417".session_id()),5,15);
				setcookie('Auth',$authh);
				setcookie('MyCookie',$login);
				$auth=fopen("data/conf/auth.var","a");
				fwrite($auth,$login."||".session_id()."||".$authh."||\n");
				fclose($auth);
				$ref=$_SERVER['HTTP_REFERER'];
				header("Location: ".$ref);
			}else{
				$ref=$_SERVER['HTTP_REFERER'];
				header("Location: ".$ref);
				echo "Nieprawidłowy login lub hasło!<br />";
			}
		}else if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth']) 
			&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
			echo "Zmiana Hasła<hr/>\n<form method=\"post\" action=\"index.php\">
			Stare Hasło: <input style=\"margin-left: 13px; width: 120px;\" type=\"password\" name=\"old_password\"/><br />
			Nowe Hasło: <input style=\"margin-left: 11px;width: 120px;\" type=\"password\" name=\"new_password\"/><br/>
			<input type=\"submit\" name=\"reg\" value=\"Zmień\">
			</form>";
		}else{
			echo "<form method=\"post\" action=\"index.php\">
			Login: <input style=\"margin-left: 12px; width: 120px;\" type=\"text\" name=\"login\"/><br />
			Hasło: <input style=\"margin-left: 11px;width: 120px;\" type=\"password\" name=\"password\"/><br/>
			<input type=\"submit\" name=\"reg\" value=\"Zaloguj\">
			</form>";
		}
			?>
		</div>
	</div>
	<div id="reg_fix">
		<div id="reg_show">
			<?php
			if(isset($_POST['login']) && isset($_POST['password'])&& !isset($_COOKIE['MyCookie']) && !isset($_COOKIE['Auth'])){
				$login=$_POST['login'];
				$pass=$_POST['password'];
				if(check_login($login) && strlen($pass)>=6 && !check_if_exists($login)){
					$plik=fopen("data/conf/users.conf","a");
					$id=last_id()+1;
					if(fwrite($plik,$id."||".$login."||".(md5("S417".$id."".substr($login,0,3)."".$pass))."||\n")){
						echo "Rejestracja zakończona pomyślnie!<br />";
					}else{
						echo "Błąd rejestracji!<br />";
					}
					fclose($plik);
					$ref=$_SERVER['HTTP_REFERER'];
					header("Location: ".$ref);
				}else{
					$ref=$_SERVER['HTTP_REFERER'];
					header("Location: ".$ref);
					echo "Nieprawidłowy login lub hasło!<br />";
				}
			}else if(isset($_POST['login']) && isset($_POST['password'])&& isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth'])
				&& check_cookie($_COOKIE['MyCookie'],$_COOKIE['PHPSESSID'],$_COOKIE['Auth'])){
				echo "Jesteś juz zarejestrowany!";
			}else{
				echo "<form method=\"post\" action=\"index.php\">
				Login: <input style=\"margin-left: 12px; width: 120px;\" type=\"text\" name=\"login\"/><br />
				Hasło: <input style=\"margin-left: 11px;width: 120px;\" type=\"password\" name=\"password\"/><br/>
				<input type=\"submit\" name=\"reg\" value=\"Rejestruj\">
				</form>";
			}
			?>
		</div>
	</div>
	<div id="show" onClick="document.getElementById('log_show').style.display='none';document.getElementById('reg_show').style.display='none'">
		<?php if(isset($_GET['cat'])){
				$cat=$_GET['cat'];
				include("gal.php");
			}else if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth']) && isset($_GET['id']) && isset($_POST['comment'])){
				$id=$_GET['id'];
				$comment=$_POST['comment'];
				include("gal.php");
			}else if(isset($_GET['id'])){
				$id=$_GET['id'];
				include("gal.php");
			}else if(isset($_COOKIE['MyCookie']) && isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['Auth']) && isset($_GET['abuse']) && isset($_POST['comment'])){
				$abuse=$_GET['abuse'];
				$comment=$_POST['comment'];
				include("gal.php");
			}else if(isset($_GET['abuse'])){
				$abuse=$_GET['abuse'];
				include("gal.php");
			}else{
				include("gal.php");
			}
		?>
	</div>
	<br />
	<div id="footer">
			Wszelkie prawa zastrzeżone itd.
		</div>
	</body>
</html>