 <?php
/*
 * Тестовое задание
 * Приложение консольное 
 * Поддержка списка пользователей github в базе данных
 * 
 */	
	
// Параметры доступа базы данных 	
	$mysql_host= "localhost";
	$mysql_db= "ivanv1_test1";
	$mysql_user= "ivanv1_test1";
	$mysql_password= "ivanv1_test1";	
	
	
// Функция получить данные с сайта
function curl_get_contents($url){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}	// end function curl_get_contents
	
	$str1= json_decode(curl_get_contents("https://api.github.com/users"));

	$db_link = mysql_connect("$mysql_host", "$mysql_user", "$mysql_password");
	if (!$db_link) {
		    die('Errors1: ' . mysql_error());
	} else {
		$db = mysql_select_db("$mysql_db", $db_link);
	}
		
	foreach ($str1 as $key => $txt) {
		$users_id= $txt->id;
		$users_login= $txt->login; 
		
		$query = "update user set github_login=\"$users_login\"  WHERE github_id=$users_id";
		$result = mysql_query($query) or die('Errors2: ' . mysql_error());	
		if (mysql_affected_rows($db_link)>0)	print "$query <br> \n";
		
		$query = "insert into user (github_login, github_id) values (\"$users_login\", $users_id)"; 
		$result = mysql_query($query);
		if (mysql_affected_rows($db_link)>0)	print "$query <br> \n";				
	}
		
	mysql_close($db_link);	
?>