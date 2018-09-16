<?php	
error_reporting(E_ALL);
ini_set('display_errors', 1);
function __autoload( $class ){
	set_include_path('/var/www/arochdi/search/');
	//$file  = preg_replace('/\\\/', '/', $class).'.php';
	$file = str_replace('\\', '/', $class);
	include_once($file.'.php');
}


// function __autoload( $class ){
// 	
	// include $class.'.php';
// }

// include('http://testserver2/arochdi/sitemapUndRobots/index.php');

//header('Content-Type: text/text; charset=utf-8');
//include('Message.php');
//\DB::getConnection();
//\Message::send();
?>


<?php

$html = '<title>CRAWLER</title>

<form action="suche.php" method="post">
<fieldset>
<legend>Wortsuche</legend>
<input type="text" name="wort" id="wort" value="" placeholder="Wort" />
<input type="submit" value="Suchen" />
</fieldset>
</form>';
// echo $html;


 function cleanURL($url){
	 $url = preg_replace('/\n/','',$url);
	 $url = preg_replace('/\r/','',$url);
	 return $url;
 }
 
 \Controller\Message::off();
 $source = 'http://testserver2/arochdi/search/sitemap.xml';
//  $source = 'http://www.scheidung.de/sitemap.xml';
		  $sitemap = array();
 		
		 // holen der Sitemap Daten
			 $xmlstr = file_get_contents($source);
			 $sitemap = new SimpleXMLElement($xmlstr);
			 $sitemap = new SimpleXMLElement($source,null,true);
			 

	
	
 		
 
// $CrawlerSentence = new \CrawlerSentence($sitemap[0]->loc);

	// Blacklist - Excluding certain paths
	/*
$Blacklist_url = array('http://www.scheidung.de/development/', 
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
		'http://www.scheidung.de/api/','http://www.scheidung.de/fileadmin/');
	 
	 */
	
$black = \Model\blacklist::loadJSONFile('json/blacklistUrl.txt');	
//$this->setBlacklist(Model_blacklist::loadJSONFile('json/blacklistContent.txt'));
	
	
 foreach($sitemap as $item){
 	
  
  $url = $item->loc;
	// if (!in_array($url, $Blacklist_url)) {
	 if (!$black->is_in($url)) {
	 
	 $url = cleanURL($url);
 
           $CrawlerWord = new \Controller\CrawlerWord($url);
  
            $CrawlerSentence = new \Controller\CrawlerSentence($url);
   
           $CrawlerContent = new \Controller\CrawlerContent($url);
			echo "\t[DONE]<br />";	
				
			}
			}

// $crawler = new Crawler('http://www.scheidung.de/wie-viel-unterhalt-muss-ich-zahlen.html');
// $url = 'http://www.scheidung.de/wie-viel-unterhalt-muss-ich-zahlen.html';
// $CrawlerSentence = new \CrawlerSentence($url);
// $CrawlerWord = new \CrawlerWord($url);
 
 
?>








