<?php

namespace Controller;
/**
 * Suchmaschinenartiger Crawler
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 04.06.2014
 */
class Crawler {

	/**
	 * Wörter welche nicht gezählt werden sollen
	 * @var array
	 */
	protected $blacklist = NULL;
	
	/**
	 * Url welche nicht gezählt werden sollen
	 * @var array
	 */
	
	
	protected $blacklist_url = array();

	/**
	 * URL der zu Crawlenden Seite
	 * @var string
	 */
	protected $url = "";

	/**
	 * Inhalt der geladenen Seite
	 * @var string
	 */
	protected $data = "";

	/**
	 * Rohdaten des Inhaltes der geladenen Seite
	 * @var string
	 */
	protected $rawData = '';

	/**
	 * Liste der allgemeinen Häufigkeiten der gefunden Wörter
	 * @var array
	 */
	//	protected $wordsGeneral = array();
	//	protected $satzGeneral = array();

	/**
	 * Liste der textauszeichnungs Häufigkeiten der gefunden Wörter
	 * @var array
	 */
	//	protected $wordsExpressions = array();
	//	protected $satzExpressions = array();

	/**
	 * Liste der Häufigkeiten der gefunden Wörter in den Titeln
	 * @var array
	 */
	//	protected $wordsHeadlines = array();
	//	protected $satzHeadlines = array();

	/**
	 * Datenbank Ressource
	 * @var mysqli
	 */
	protected $mysqli = NULL;

	/**
	 * URL-ID der Seite in der Datenbank
	 * @var int
	 */
	protected $fk_url_page = 0;
	
	
	/**
	 * verhindert umbruch der zeilen
	 * @param string $url Die Ziel Adresse
	 * @return void
	 */

 // protected static function cleanURL($url){
	// $url = preg_replace('/\n/','',$url);
	// $url = preg_replace('/\r/','',$url);
	// return $url;
 // }

	/**
	 * Setzt des blacklist
	 * @param array $blacklist Der $blacklist
	 * @return void
	 */
	 public function setBlacklist($blacklist) {
		$this -> blacklist = $blacklist;
	}
	 
	 /**
	 * Setzt des blacklist_url
	 * @param array $blacklist_url Der $blacklist
	 * @return void
	 */
	 public function setBlacklist_url($blacklist_url) {
		$this -> blacklist_url = (array)$blacklist_url;
	}

	/**
	 * Liefert die Blacklist
	 * @return array
	 */
	public function getBlacklist() {
		return $this -> blacklist;
	}
	/**
	 * Liefert die Blacklist_url
	 * @return array
	 */
	public function getBlacklist_url() {
		return $this -> blacklist_url;
	}
	
	/**
	 * Setzt die zu Crawlende Adresse
	 * @param string $url Die Ziel Adresse
	 * @return void
	 */
	public function setUrl($url) {
		$this -> url = (string)$url;
	}

	/**
	 * Erhält das Inhalt von URL
	 * @param String $url Der Link
	 * @return void
	 */

	public function getUrl() {
		return $this -> url;
	}

	/**
	 * Setzt der DATA
	 * @param String $data Die Seite
	 * @return void
	 */
	public function setData($data) {
		$this -> data = (string)$data;
	}
    public function setrawData($rawData) {
		$this -> rawData = (string)$rawData;
	}


	/**
	 * Erhält das Inhalt von DATA
	 * @param String $data Die Seite
	 * @return void
	 */

	public function getData() {
		return $this -> data;
	}
	public function getrawData() {
		return $this -> rawData;
	}

	/**
	 * Setzt der DATENBANK MYSQLI
	 * @param Array $mysqli Die Seite
	 * @return void
	 */
	public function setMysqli($mysqli) {
		if (is_a($mysqli, 'mysqli')) {
			$this -> mysqli = $mysqli;
		}
	}

	/**
	 * Erhält die Information von $mysqli
	 * @param array $mysqli Die Seite
	 * @return void
	 */
	public function getMysqli() {
		return $this -> mysqli;
	}

	/**
	 * Setzt die URL-ID
	 * @param int $id Die URL-ID
	 * @return void
	 */
	public function set_fk_url_page($id) {
		$this -> fk_url_page = (int)$id;
	}

	/**
	 * Liefert die URL-ID
	 * @return int
	 */
	public function get_fk_url_page() {
		return $this -> fk_url_page;
	}

	/**
	 * Erzeugt eine Instanz der Klasse
	 * @param string URL der Seite welche gecrawled werden sollen
	 * @return Crawler
	 */
	public function __construct($url) {
		Message::send("Start with URL\t\t: " . $url . "\t");
		$this -> setMysqli(\Controller\DB::getConnection('DB_search'));
		Message::send("Connect to Database\t: DB_search");
		/*
		$this->setBlacklist_url(array('http://www.scheidung.de/development/', 
		'http://www.scheidung.de/uploads/', 'http://www.scheidung.de/include/',
	    'http://www.scheidung.de/t3lib/', 'http://www.scheidung.de/typo3/',
	    'http://www.scheidung.de/typo3temp/', 'http://www.scheidung.de/piwik/',
	    'http://www.scheidung.de/elitexperts-profileditor/', 'http://www.scheidung.de/scheidungsapp/', 
	    'http://www.scheidung.de/kundenmeinungen-erfahrungen/', 'http://www.scheidung.de/scheidungsapp/',
	    'http://www.scheidung.de/blog_old/','http://www.scheidung.de/scripts/',
		'http://www.scheidung.de/api.php','http://www.scheidung.de/ajax.php',
		'http://www.scheidung.de/default.php','http://www.scheidung.de/helper.php',
		'http://www.scheidung.de/km.php','http://www.scheidung.de/livewatch.php',
		'http://www.scheidung.de/mnuTest.php','http://www.scheidung.de/old_index.php',
		'http://www.scheidung.de/mail','http://www.scheidung.de/phpinfo.php',
		'http://www.scheidung.de/slider-content.html','http://www.scheidung.de/mittlere-navigation.html',
		'http://www.scheidung.de/api/','http://www.scheidung.de/fileadmin/'));*/
		
	//	self::cleanURL($url);
		$this -> setUrl($url);
		$data='';
		$data = @file_get_contents((string)$url);
		if($data==''){
			$file = fopen('log/error.txt','a+');
			flock($file,2);
			fputs($file,"[".time('d.m.Y H:i:s')."] Can't open File: $url  \n");
			flock($file,3);
			fclose($file);
		} else {
			$this->rawData = $data;
	
			$start = strpos($data, '<!--TYPO3SEARCH_begin-->');
	
			if (strrpos($data, '<section id="lower_buttons_hr">') != FALSE) {
				$ende = strrpos($data, '<section id="lower_buttons_hr">') - $start;
			} else {
				$ende = strrpos($data, '<!--TYPO3SEARCH_end-->') - $start;
			}
			$data = substr($data, $start, $ende);
			$data = preg_replace('/<script>([^<]*)<\/script>/mi', '', $data);
			$data = preg_replace('/<style([^<]*)<\/style>/mi', '', $data);
			$data = strip_tags($data, '<h1><h2><h3><h4><h5><h6><b><strong><i><a>');
			$data = html_entity_decode($data);
	
			$this -> setData($data);
			$this -> updateTime();
		//	$this -> deletePreviousEntries();
			$this -> update_frequency_url();
			
		}
	}
	/**
	 * Erzeugt eine Liste der Link-Adressen und deren Häufigkeit
	 * @return array
	 */
	public function getHyperlinks() {
		$anzahlurl = array();
		preg_match_all('/<a\s*href=\"([a-z0-9.:#-_\/\[\]=?]*)\"/im', $this -> data, $ergebnisurl);
		foreach ($ergebnisurl[1] as $item) {
			if (!isset($anzahl[$item])) {
				$anzahlurl[$item] = 0;
			}
			$anzahlurl[$item]++;
		}

		return $anzahlurl;
	}
	
	protected function Blacklist_Excluding_url(){
		
		$this->loadJSONFile($filename);
	}

	/**
	 * Erzeugt eine Liste von Häufigkeiten anhand eines Regulären Ausdrucks.
	 * @param string $regex Regulärerausdruck
	 * @param int $regexPosition Gibt die gewünschte Regex Position an.
	 * @return array
	 */
	protected function makeRegexList($regex, $regexPosition, $htmlClear = FALSE) {
		$liste = array();
		$data = $this -> getData();
		if ($htmlClear) {
			$data = strip_tags($data);
		}
		preg_match_all($regex, $data, $ergebnis);
		foreach ($ergebnis[$regexPosition] AS $item) {
			$item = strtolower($item);
			if (!$this->getBlacklist()->is_in($item)) {
				if (!isset($liste[$item])) {
					$liste[$item] = 0;
				}
				$liste[$item]++;
			}
		}
		return $liste;
	}

	/**
	 * Erzeugt eine Liste von Häufigkeiten anhand eines Regulären Ausdrucks.
	 * @param string $regex Regulärerausdruck
	 * @param int $regexPosition Gibt die gewünschte Regex Position an.
	 * @return array
	 */
	protected function makeRegexListOnes($regex) {
		$liste = array();
		preg_match_all($regex, $this->rawData, $liste);
		if (isset($liste[2][0])){
			return $liste[2][0];
		} else {
			return '';
		}
	}

	/**
	 * Setzt die Aktuelle Zeit für das Crawlen der URL in der Datenbank
	 * @return void
	 */
	public function updateTime() {
		$url = $this -> getUrl();
		$stmt = $this -> getMysqli() -> prepare('INSERT INTO frequency_time (url,cr_date,last_update) VALUES (?,NOW(),NOW()) ON DUPLICATE KEY UPDATE last_update = NOW()');
		$stmt -> bind_param('s', $url);
		$stmt -> execute();
		$stmt -> close();
	}

	/**
	 * Löscht nicht mehr notwendige Einträge
	 * @return void
	 */
	// public function deletePreviousEntries() {
		// $url = $this -> getUrl();
		// $stmt = $this -> getMysqli() -> prepare("DELETE FROM frequency WHERE url = ?");
		// $stmt -> bind_param('s', $url);
		// $stmt -> execute();
		// $stmt -> close();
	// }

	/**
	 * Setzt die URL in die Datenbank und holt sich die dazugehörige ID
	 * @return void
	 */
	public function update_frequency_url() {
		Message::send("\nGefundene Link\t: \n=================");
		$url = $this -> url;
		$fk_url_page = 0;
		$stmt = $this -> getMysqli() -> prepare("SELECT id FROM frequency_url WHERE url= ? ");
		$stmt -> bind_param('s', $url);
		$stmt -> execute();
		$stmt -> bind_result($id);
		if ($stmt -> fetch()) {
			$stmt->prepare('update frequency
						SET frequency_total = ?,frequency_headlines = ?,frequency_expressions = ?
						WHERE id = ?');
			$stmt->bind_param('iiii',$frequencyTotal,$frequencyExpression,$frequencyHeadlines,$id);
			Message::send("=>" . $url . "=>");
		} else {
			
			$stmt = $this -> getMysqli() -> prepare("INSERT INTO frequency_url (url) VALUES (?) ");
			$stmt -> bind_param('s', $url);
			$stmt -> execute();
			$fk_url_page = $this -> getMysqli() -> insert_id;
			$stmt -> close();

		}
		$this -> set_fk_url_page($fk_url_page);
		Message::send($this -> get_fk_url_page() . "==>");
		//echo $this->get_fk_url_page();
	}

	/**
	 * Speichert die gefundene Link-Adressen zur Seite in der Datenbank
	 * @return void
	 */
	

}

?>