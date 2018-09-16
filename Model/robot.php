<?php
class robot 
{
	/**
	 * Adresse der robots.txt Datei
	 * @var string
	 */
	protected $urlr = 'http://www.scheidung.de/robots.txt';
	
	protected $allow = array();
	
	protected $disallow = array();
		
	/**
	* Liefert alle erlaubten Elemente als Liste
	* @return array
	*/
	public function getAllow() {
		return $this -> allow;
	}
	
	public function getDisallow() {
		return $this -> disallow;
	}
		
	/**
	* Setzt eine Liste aller erlaubten Elemente
	* @param array $allow Die Liste
	* @return boolean
	*/
	public function setAllow($allow) {
		return $this -> allow = (array)$allow;
	}
			
	public function setDisallow($disallow) {
		$this -> disallow = (array)$disallow;
	}
			
	public function __construct(){		
		// robots.txt einlesen
		$fpr = fopen($this->urlr, 'rb');
		$datar = stream_get_contents($fpr);

		// Befüllen der erlaubt Liste
		preg_match_all('/Allow\: ([a-z0-9_\-.\/]{3,})/m', $datar, $ergebnisa);
		$this->setAllow($ergebnisa[1]);

		// Befüllen der nicht erlaubt Liste
		preg_match_all('/Disallow\: ([a-z0-9_\-.\/]{3,})/mi', $datar, $ergebnisd);
		$this->setDisallow($ergebnisd[1]);
	}
	
} 
?>