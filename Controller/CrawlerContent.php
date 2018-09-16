<?php
namespace Controller;
/**
 * Suchmaschinenartiger Crawler
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 04.06.2014
 */
class CrawlerContent extends Crawler{

	/**
	 * klein Beschreibung von dem Link
	 * @var array
	 */
	protected $contentDescription = '';
	
	/**
	 * Liste der Häufigkeiten der gefunden Title 
	 * @var array
	 */
	protected $contentHeadlines = '';
	
	/**
	 * Erzeugt eine Instanz der Klasse
	 * @param string URL der Seite welche gecrawled werden sollen
	 * @return CrawlerContent
	 */
	public function __construct($url) {
		$this->setBlacklist(array('der', 'die', 'das', 'von', 'zu', 'in', 'wer', 'wie', 'was', 'navigationsmenü', '?','czoxndoizglzywjszulwq','czoxoiixijtzojg',
		'czoyoijkzsi','fydglrzwwuahrtbci','hcnrpa','hefzhbhvlijtzoje','hhyxauy','ijuio','ikvyvdpyyxrpbmdzl','imfkzgl','imluy','imzpbgvhzg','inbpzci','injhdgluz',
		'jlcy','lkdggio','ltywdlv','mijthojk','mjoimjuio','msie','mtoimsi','mudhhfcmf','ndoibgfuzyi','ndoiyxv','nzijtzojq','oijjb','oijtaw','uywxdu','wateucghwijtzojewoijzdg',
		'wbgf','wywx','ytozontzojm','yywdluglkijtpojizmttzojeyoij','zgvmawjzijtzojqwoijfwfq','zuzpbguio','zxmvcmf','pkznnz','submit','src','true','www','script','czo','mte',
		'cmf','ncy','xhc','ndg','pbi','vsl','zsi','mty','mtm','mio','mjk','vzguio','byi','hly','sio','push','gtm','start','new','date','gettime','event','var','getelementsbytagname',
		'createelement','datalayer','async','googletagmanager','com','window','insertbefore','parentnode','href','http','title','html','online','header','link','faqs','faq','tÜv','wenn',
		'über','javascript','void','pages','fxm','bfa','ddf','ebc','tun','https','tworows','dol','browser','submenu','click','this','css','img','styles','layouts','col','img',
		'arrow','down','png','slidedown','weitere','peters'));
		
		$this->setBlacklist(\Model\blacklist::loadJSONFile('json/blacklistContent.txt'));
		
		parent::__construct($url);
       
	  echo $url;
 		$this->contentHeadlines = $this->makeRegexListOnes('/\<title>((([^<]{3,})+)*)<\/title\>/mi');
		
	//	var_dump($this->contentHeadlines);
		
		$this->contentDescription = $this->makeRegexListOnes('/\<meta name="description" content="((([^<]{3,})+)*)\/>/mi');
		
	//	var_dump($this->contentDescription);
		
		$this->setContentInDatabase();
		
	//	var_dump($this->getData);
		
		
		
		}
	
 
/**
	 * Setzt die URL,Title,Desccription und Data in die Datenbank 
	 * @return void
	 */
	
	
	public function setContentInDatabase(){
		Message::send("\nGefundene Content\t: \n========================");
		
		$url = $this->url;
		var_dump($url);
		$title = $this->contentHeadlines;
//		var_dump($title);
		$description =$this->contentDescription;
	//	var_dump($description);
		$data= strip_tags($this->data);
//		var_dump($data);
	
		
	
			$stmt = $this -> getMysqli() -> prepare("INSERT INTO PageView(url, title, description,content) VALUES(  ?, ?, ?, ? )");
		    $stmt -> bind_param('ssss', $url, $title, $description, $data);
			$stmt -> execute();
			$stmt -> close();
		
	}

    

	

	
}
 



?>
