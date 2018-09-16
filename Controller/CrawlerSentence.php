<?php
namespace Controller;
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/text; charset=utf-8');
//include('DB.php');
//include('Message.php');
//include('Crawler.php');
/**
 * Suchmaschinenartiger Crawler
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 04.06.2014
 */
class CrawlerSentence extends Crawler {
	 	
    // Crawler::getUrl('Crawler.php');
// 	
	// Crawler::getData('Crawler.php');
// 	
	// Crawler::getMysqli('Crawler.php');
// 	
	// Crawler::get_fk_url_page('Crawler.php');

	/**
	 * Wörter welche nicht gezählt werden sollen
	 * @var array
	 */
	protected $blacklist = array();
	
	/**
	 * Liste der allgemeinen Häufigkeiten der gefunden Wörter
	 * @var array
	 */

	protected $satzGeneral = array();
	
	/**
	 * Liste der textauszeichnungs Häufigkeiten der gefunden Wörter
	 * @var array
	 */

	protected $satzExpressions = array();
	
	/**
	 * Liste der Häufigkeiten der gefunden Wörter in den Titeln
	 * @var array
	 */

	protected $satzHeadlines = array();
	
	
	
	/**
	 * Setzt des blacklist
	 * @param array $blacklist Der $blacklist
	 * @return void
	 */
	 public function setBlacklist($blacklist) {
		$this -> blacklist = new \Model\blacklist($blacklist);
	}

	/**
	 * Liefert die Blacklist
	 * @return array
	 */
	public function getBlacklist() {
		return $this -> blacklist;
	}
	
	

	
	
	
	

	

	
	
	 
	
	
	/**
	 * Erzeugt eine Instanz der Klasse
	 * @param string URL der Seite welche gecrawled werden sollen
	 * @return Crawler1
	 */
	public function __construct($url) {
		Message::send("Start with URL\t\t: " . $url ."\t");
		$this->setMysqli(DB::getConnection('DB_search'));	
		Message::send("Connect to Database\t: DB_search");
		/*
		$this->setBlacklist(array('der', 'die', 'das', 'von', 'zu', 'in', 'wer', 'wie', 'was', 'navigationsmenü', '?','czoxndoizglzywjszulwq','czoxoiixijtzojg',
		'czoyoijkzsi','fydglrzwwuahrtbci','hcnrpa','hefzhbhvlijtzoje','hhyxauy','ijuio','ikvyvdpyyxrpbmdzl','imfkzgl','imluy','imzpbgvhzg','inbpzci','injhdgluz',
		'jlcy','lkdggio','ltywdlv','mijthojk','mjoimjuio','msie','mtoimsi','mudhhfcmf','ndoibgfuzyi','ndoiyxv','nzijtzojq','oijjb','oijtaw','uywxdu','wateucghwijtzojewoijzdg',
		'wbgf','wywx','ytozontzojm','yywdluglkijtpojizmttzojeyoij','zgvmawjzijtzojqwoijfwfq','zuzpbguio','zxmvcmf','pkznnz','submit','src','true','www','script','czo','mte',
		'cmf','ncy','xhc','ndg','pbi','vsl','zsi','mty','mtm','mio','mjk','vzguio','byi','hly','sio','push','gtm','start','new','date','gettime','event','var','getelementsbytagname',
		'createelement','datalayer','async','googletagmanager','com','window','insertbefore','parentnode','href','http','title','html','online','header','link','faqs','faq','tÜv','wenn',
		'über','javascript','void','pages','fxm','bfa','ddf','ebc','tun','https','tworows','dol','browser','submenu','click','this','css','img','styles','layouts','col','img',
		'arrow','down','png','slidedown','weitere','peters'));
		  
		 */
	$this->setBlacklist(\Model\blacklist::loadJSONFile('json/blacklistContent.txt'));
		
		parent::__construct($url);
		
		
				
		$this->satzGeneral = $this->makeRegexList('/([^,.;?:<>]+)*/mi',1,TRUE);
		
		$this->satzHeadlines = $this->makeRegexList('/<[hH]([1-6])>((([^<>]{3,})+)*)<\/[hH]\1>/im',0);
		
		$this->satzExpressions = $this->makeRegexList('/\<(b|strong|i)>((([^<]{3,})+)*)<\/(b|strong|i)\>/mi',2);
		
		
		
		$this->updateTime();
	//	$this->deletePreviousEntries();
		$this->setFrequencyInDatabase();
//		$this->update_frequency_sentence($url,$satz,$frequencyGeneral,$frequencyExpression,$frequencyHeadlines);
	
		}
	
	
	/**
	 * Erzeugt eine Liste der Link-Adressen und deren Häufigkeit
	 * @return array
	 */
	public function getHyperlinks() {
		$anzahlurl = array();
		preg_match_all('/<a\s*href=\"([a-z0-9.:#-_\/\[\]=?]*)\"/im', $this->data , $ergebnisurl);
		foreach ($ergebnisurl[1] as $item) {
			if (!isset($anzahl[$item])) {
				$anzahlurl[$item] = 0;
			}
			$anzahlurl[$item]++;
		}
		
		return $anzahlurl;
	}
	
	
	/**
	 * Erzeugt eine Liste von Häufigkeiten anhand eines Regulären Ausdrucks.
	 * @param string $regex Regulärerausdruck
	 * @param int $regexPosition Gibt die gewünschte Regex Position an.
	 * @return array
	 */	
	
	
	/**
	 * Setzt die Aktuelle Zeit für das Crawlen der URL in der Datenbank
	 * @return void
	 */
	public function updateTime(){
		$url = $this->getUrl();
		$stmt = $this -> getMysqli() -> prepare('INSERT INTO frequency_time (url,cr_date,last_update) VALUES (?,NOW(),NOW()) ON DUPLICATE KEY UPDATE last_update = NOW()');
		$stmt -> bind_param('s', $url);
		$stmt -> execute();
		$stmt -> close();
	}

	/**
	 * Löscht nicht mehr notwendige Einträge
	 * @return void
	 */
	// public function deletePreviousEntries(){
		// $url = $this->getUrl();
		// $stmt = $this -> getMysqli() -> prepare("DELETE FROM frequency WHERE url = ?");
		// $stmt -> bind_param('s', $url);
		// $stmt -> execute();
		// $stmt -> close();
	// }
	
	
	public function setFrequencyInDatabase(){
		Message::send("\nGefundene Satz\t: \n========================");
	//	var_dump($this->satzGeneral);
		foreach ($this->satzGeneral AS $key => $value) {
			//echo $key . '|' .$value;
			$satz = $key;
			$url = $this->getUrl();
			$frequencyGeneral = $value;
	
			$frequencyExpression = (isset($this->satzExpressions[$key]) && $this->satzExpressions[$key] !== NULL) ? $this->satzExpressions[$key] : 0;
			$frequencyHeadlines = (isset($this->satzHeadlines[$key])) ? $this->satzHeadlines[$key] : 0;
		    $this -> update_frequency_satz($url, $satz, $frequencyGeneral, $frequencyHeadlines, $frequencyExpression);

		}
	}

	/**
	 * Setzt die Update von frequency_satz
	 * @return void
	 */
	public function update_frequency_satz($url, $satz, $frequencyGeneral, $frequencyHeadlines, $frequencyExpression){
		$url = $this->url;	
		$stmt = $this -> getMysqli() -> prepare("SELECT id FROM frequency_satz WHERE url= ? and satz= ? ");
		$stmt -> bind_param('ss', $url, $satz);
		$stmt -> execute();
		$stmt -> bind_result($id);
		if ($stmt -> fetch()) {
			$stmt -> prepare('update frequency_satz 
			              set frequency_general = ?,frequency_headlines = ?,frequency_expressions = ?   
			              where id = ?');
			$stmt -> bind_param('iiii',$frequencyGeneral,$frequencyHeadlines, $frequencyExpression, $id);                   	
		} else {
			$stmt = $this -> getMysqli() -> prepare("INSERT INTO frequency_satz (url, satz, frequency_general,
			                                         frequency_expressions, frequency_headlines)
			                                         VALUES (?,?,?,?,?) ");
			$stmt -> bind_param('ssiii',$url,$satz,$frequencyGeneral, $frequencyHeadlines, $frequencyExpression);
			$stmt -> execute();	
			$stmt -> close();		
		}
		
	}

	/**
	 * Speichert die gefundene Link-Adressen zur Seite in der Datenbank
	 * @return void
	 */
	
}
 //  Message::on();

   Message::off();



?>
