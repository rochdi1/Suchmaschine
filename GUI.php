<?php	
error_reporting(E_ALL);
ini_set('display_errors', 1);
function __autoload( $class ){
	set_include_path('/var/www/arochdi/search/');
	$file = str_replace('\\', '/', $class);
	include_once($file.'.php');
}

//include "/Controller/Ergebnis.php";
if(!isset($_POST['submit'])){
// echo '<form action="Controller/Ergebnis.php" method="post">';
echo '<form action="test1.php" method="post">';
echo '<fieldset>';
echo '<legend>Wortsuche</legend>';
echo '<input type="text" name="wort" id="wort" value="" placeholder="Wort" />';
echo '<input type="submit" value="Suchen" />';
echo '</fieldset>';
echo '</form>';
}
?>