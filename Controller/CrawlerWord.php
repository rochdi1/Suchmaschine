<?php
namespace Controller;
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/text; charset=utf-8');

/**
 * Suchmaschinenartiger Crawler
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.317
 * @date 04.06.2014
 */
class CrawlerWord extends Crawler {

	/**
	 * Wörter welche nicht gezählt werden sollen
	 * @var array
	 */
	protected $blacklist = array();

	/**
	 * Liste der allgemeinen Häufigkeiten der gefunden Wörter
	 * @var array
	 */
	protected $wordsGeneral = array();

	/**
	 * Liste der textauszeichnungs Häufigkeiten der gefunden Wörter
	 * @var array
	 */
	protected $wordsExpressions = array();

	/**
	 * Liste der Häufigkeiten der gefunden Wörter in den Titeln
	 * @var array
	 */
	protected $wordsHeadlines = array();

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
		Message::send("Start with URL\t\t: " . $url . "\t");
		$this -> setMysqli(DB::getConnection('DB_search'));
		Message::send("Connect to Database\t: DB_search");
		/*
		 $this->setBlacklist = new \Model\blacklist(array('der', 'die', 'das', 'von', 'zu', 'in', 'wer', 'wie', 'was', 'navigationsmenü', '?','czoxndoizglzywjszulwq','czoxoiixijtzojg',
		 'czoyoijkzsi','fydglrzwwuahrtbci','hcnrpa','hefzhbhvlijtzoje','hhyxauy','ijuio','ikvyvdpyyxrpbmdzl','imfkzgl','imluy','imzpbgvhzg','inbpzci','injhdgluz',
		 'jlcy','lkdggio','ltywdlv','mijthojk','mjoimjuio','msie','mtoimsi','mudhhfcmf','ndoibgfuzyi','ndoiyxv','nzijtzojq','oijjb','oijtaw','uywxdu','wateucghwijtzojewoijzdg',
		 'wbgf','wywx','ytozontzojm','yywdluglkijtpojizmttzojeyoij','zgvmawjzijtzojqwoijfwfq','zuzpbguio','zxmvcmf','pkznnz','submit','src','true','www','script','czo','mte',
		 'cmf','ncy','xhc','ndg','pbi','vsl','zsi','mty','mtm','mio','mjk','vzguio','byi','hly','sio','push','gtm','start','new','date','gettime','event','var','getelementsbytagname',
		 'createelement','datalayer','async','googletagmanager','com','window','insertbefore','parentnode','href','http','title','html','online','header','link','faqs','faq','tÜv','wenn',
		 'über','javascript','void','pages','fxm','bfa','ddf','ebc','tun','https','tworows','dol','browser','submenu','click','this','css','img','styles','layouts','col','img',
		 'arrow','down','png','slidedown','weitere','peters'));

		 */
		$this -> setBlacklist(\Model\blacklist::loadJSONFile('json/blacklistContent.txt'));

		parent::__construct($url);

		$this -> wordsGeneral = $this -> makeRegexList('/([a-zA-ZöäüÖÄÜß]){3,}/im', 0);

		$this -> wordsHeadlines = $this -> makeRegexList('/<[hH]([1-6])>([^<]{3,})<\/[hH]\1>/im', 0);

		$this -> wordsExpressions = $this -> makeRegexList('/\<(b|strong|i)>([^<]{3,})<\/(b|strong|i)\>/mi', 2);

		$this -> updateTime();
		$this -> setFrequencyInDatabase();

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
	 * Speichert die Ergebnisse der Wörtersuche in der Datenbank
	 * @return void
	 */
	public function setFrequencyInDatabase() {
		Message::send("\nGefundene Wörter\t: \n========================");
		foreach ($this->wordsGeneral AS $key => $value) {
			$word = $key;
			$url = $this -> getUrl();
			$frequencyTotal = $value;
			$frequencyExpression = (isset($this -> wordsExpressions[$key])) ? $this -> wordsExpressions[$key] : 0;
			$frequencyHeadlines = (isset($this -> wordsHeadlines[$key])) ? $this -> wordsHeadlines[$key] : 0;
			$this -> update_frequency_wort($url, $word, $frequencyTotal, $frequencyHeadlines, $frequencyExpression);
		}
	}

	public function update_frequency_wort($url, $word, $frequencyTotal, $frequencyHeadlines, $frequencyExpression) {
		$url = $this -> url;
		$stmt = $this -> getMysqli() -> prepare("SELECT id FROM frequency WHERE url= ? AND wort = ? ");
		$stmt -> bind_param('ss', $url, $word);
		$stmt -> execute();
		$stmt -> bind_result($id);
		if ($stmt -> fetch()) {
			$stmt -> prepare('update frequency
						SET frequency_total = ?,frequency_headlines = ?,frequency_expressions = ?
						WHERE id = ?');

			$stmt -> bind_param('iiii', $frequencyTotal, $frequencyHeadlines, $frequencyExpression, $id);
		} else {
			$stmt = $this -> getMysqli() -> prepare("INSERT INTO frequency (url,wort,frequency_total,
			                                            frequency_headlines,frequency_expressions) 
			                                            VALUES (?,?,?,?,?)");
			$stmt -> bind_param('ssiii', $url, $word, $frequencyTotal, $frequencyHeadlines, $frequencyExpression);
			$stmt -> execute();
			$stmt -> close();
		}
	}
}
?>