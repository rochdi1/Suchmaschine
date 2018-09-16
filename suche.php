<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function makeBold($pattern, $subject){
	$str = preg_replace_callback('/'.$pattern.'/im', function($treffer){
		return '<b>'.$treffer[0].'</b>';
	}, $subject);
	return $str;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>

		<title>Suchfunktion </title>
	</head>

	<body>

		<?php 
//* Datenbankverbindung aufbauen (START) 

$verbindung = mysql_connect ("localhost", "root", "addedlifevalue"); 

mysql_select_db("DB_search") ; 

//* Datenbankverbindung aufbauen (ENDE) 

if(isset($_POST['wort'])){
    $wort = mysql_real_escape_string($_POST['wort']); 
} else {
	$wort = '';
}
//* Überprüfung der Eingabe     
//    $query = "SELECT wort, url, title, description, content FROM view_search WHERE wort = '$wort'";
    $query = "SELECT DISTINCT v.wort, v.url, v.title, v.description FROM view_search v WHERE v.wort = '$wort'";
    $query2 = "SELECT DISTINCT v.wort, v.url, v.title, v.description FROM view_search v WHERE v.wort IN (	SELECT p.wort FROM Pseudonyme p JOIN Pseudonyme l ON FIND_IN_SET(p.id, l.relation) AND l.wort = '$wort')";
  
	                    
	// echo $query; 
	
	//	var_dump($query);
	
    $result = mysql_query($query); 
//	var_dump($result);
    $flag = FALSE;
	while($object = mysql_fetch_object($result)){
		//	var_dump($object);
		
		$flag = TRUE;
		echo $object->wort.'<br />';
		echo '<p><span style="color: blue;">'.makeBold($wort,$object->title) . '</span><br />';
		echo '<span style="color: green;font-size: .75em;">'.makeBold($wort,$object->url) . '</span><br />';
		echo makeBold($wort,$object->description) . '</p>';
	    //echo '*  *  *  *  *  *'. '<br />';
	    
		
	}
             
?>
	</body>
</html>





