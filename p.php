<style>
table, td {
    border: 2px solid black;
}

table {
    width: 100%;
}

td {
	width: 200px;
	
}
input[type='text']
{
background:red;
}
</style>


<?php
mysql_connect("localhost","root","addedlifevalue") or die ("problem with connection...");
mysql_select_db("DB_search");

//var_dump($_POST);
if(isset($_POST['wortid']) ){		
 $query4 ="CALL insertPseudonyme('".$_POST['neuewort'] ."',". $_POST['wortid'] .")";
 //	echo $query4;
	$result4 = mysql_query($query4);
}

$Step = 6;
$pages_query = mysql_query("SELECT COUNT('ID') FROM Pseudonyme ");
//$pages = ceil(mysql_result($pages_query, 0) / $per_page);
$pages = ceil(mysql_result($pages_query, 0) / $Step);

$seite = (isset($_GET['seite'])) ? (int)$_GET['seite'] : 1;
$start = ($seite - 1) * $Step;


$query = mysql_query("SELECT id, wort FROM Pseudonyme LIMIT $start, $Step ");
echo'<fieldset><legend>Synonymme_Liste</legend>';
while($result = mysql_fetch_object($query)){
	
	echo'<form action="p.php'.((isset($_GET['seite']))?'?seite='.$_GET['seite']:'').'" method="post">';
	
	echo'<table ><tr><td ">';
	echo $result->wort.'</td>';
	echo '<td><select name="select" id="pseudolist" >';
		$query2 = "SELECT wort FROM  `Pseudonyme` WHERE FIND_IN_SET( ".$result->id.", relation)";
		$result2 = mysql_query( $query2 );
		while($object2 = mysql_fetch_object($result2)){
			echo '<option id="pid_'.$object2->wort.'">'.$object2->wort.'</option>';
		 }
		echo '</select></td>';
		
		
		
		echo '<td ><input type="text" id="neuewort" name="neuewort" value="'.$neuewort.'"></td>';
		echo '<td ><input type="submit" id="envoiMessage" name="submit" value="Speichern  "></td>';
		echo '<input type="hidden" name="wortid" id="wortid" value="'.$result->id.'"/>';
		
		
		
		
		echo'</tr></table>';
		
		echo'</form>';
		

		
		
		
			
		
}
echo'</fieldset>';
$prev = $seite - 1;
$next = $seite + 1;
if(!($seite <=1)){
echo "<a href='p.php?seite=$prev'>Prev</a> ";
}
	
	
if($pages >= 1){
for($x= 1;$x<=$pages;$x++){
	
	echo ($x == $seite) ? '<b><a href="?seite='.$x.'">'.$x.'</a></b> ':'<a href="?seite='.$x.'">'.$x.'</a> ';
	
}
}
if(!($seite>=$pages)){
echo "<a href='p.php?seite=$next'>Next</a> ";
}



?>
