<?php
namespace Controller;

/**
 * Blacklist
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 06.10.2014
 */
class Ergebnis {


     /**
	 * Wort inhalt die Wortesuche
	 * @var string
	 */

	Protected $wort = '';
	
	/**
	 * Collection inhalt alle content von title description un url
	 * @var array
	 */

	Protected $collection = NULL;
	
	
	

	public function __construct($wort) {
        
		$this -> searchabfrage($wort);
		Message::send("Connect to Database\t: DB_search");
	}
   
   /**
	 * Suche die wort in die Datenbank und holt sich die dazugehörige title,description und link
	 * @var void
	 */
	public function searchabfrage($wort) {
	//	echo $wort;
		$this->collection=new \Model\ErgebnisCollection();
	//	echo $wort;
		$con = \Controller\DB::getConnection('DB_search');
		$stmt = $con -> prepare("SELECT DISTINCT v.url, v.title, v.description
                  FROM view_search v WHERE v.wort = ?");
		$stmt -> bind_param('s', $wort);
		$stmt -> execute();
	//	var_dump($stmt);
		$stmt->bind_result($url,$title,$description);
		
		while($stmt->fetch()){
			$this->collection->addItem(new \Model\Ergebnis($url,$title,$description));
			// echo $url.'</br>';
			// echo $title.'</br>';
			// echo $description.'</br>';
			// echo '[Done]'.'</br>';
		}
		$stmt -> execute();
		$stmt -> close();
	}


    /**
	 * Setzt das Wort von die suche
	 * @param string $wort 
	 * @return void
	 */
	public function setWort($wort) {
		$this -> wort = (string)$wort;
	}

	/**
	 * Erhält das Inhalt von wort
	 * @param String $wort
	 * @return void
	 */
	public function getwort() {
		return $this -> wort;
	}

     /**
	 * Returns the string representation of the ReflectionClass object
	 * @return void
	 */
  
	public function __tostring(){
		
		$out  = '';
		$out = \View\Ergebnis::ergebnislist($this->getwort(), $this->collection);
	
		var_dump($out);
		
		//Zeichenkette zurückliefern
		return (string)$out;
	}

}


?>

