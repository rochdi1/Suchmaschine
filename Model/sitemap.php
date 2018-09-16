<?php


class sitemap {

		protected $source = 'http://testserver2/arochdi/Optimiertesuchmaschine2/sitemap.xml';
        
		protected $sitemap = array();
		
		public function getSitemap(){
			return $this->sitemap;
		}
		public function setSitemap( $sitemap ){
			$this->sitemap = (array)$sitemap;
		}
		public function addToSitemap( $item ){
			array_push($this->sitemap, (string)$item );
		}
		
		protected $robot = null;
		
		public function getRobot(){
			return $this->robot;
		}
		
		public function setRobot(\robot $robot ){
			$this->robot = $robot;
		}
       
	   /**
	   * Liefer alle Informationen zu einer Adresse
	   * Example:
	   *  $object -> protocoll 			Protokoll der Adress (http|https)
	   *  $object -> domain 			Domain der Adresse (www.test.de)
	   *  $object -> filename			Dateiname innerhalt der Addresse
	   *  $object -> directorys         Liste(Array) der Verzeichnisse
	   * @attribute 
	   * @param string $url Die zu verarbeitende Adresse
	   * @return stdClass
	   */
	   protected function urlInfo($url){
			$path = new stdClass();
			$path->protocoll = 'http';
			
			if(substr($url,0,5) == 'https') {
				$path->protocoll = 'https';
			}
							
			$array = preg_split('/\//',$url);
			
			$path->domain = $array[0];
			$path->filename = $array[sizeof($array)-1];
			$path->directorys = array_slice( $array, 1, sizeof($array) -2 );
			
			return $path;
		}
	   
		function isDisallow($url){		
			foreach ($this->getRobot()->getDisallow() as $disallowItem){							
				if(substr($url, 0, strlen($disallowItem)) == $disallowItem ){
					return true;
				}
			}
		}
	   
		public function __construct(\robot $robot){
		
			// Setzen der robots.txt Klasse
			$this->setRobot( $robot );
		
			// holen der Sitemap Daten
			$xmlstr = file_get_contents($this->source);
			$sitemap = new SimpleXMLElement($xmlstr);
			$sitemap = new SimpleXMLElement($this->source,null,true);

			foreach($sitemap as $item){
				$this->addToSitemap($item->loc);
				echo $item->loc.'</br>';
			}
			

	}
	
	public function proofDisallow(){
		$list = array();
		foreach($this->getSitemap() as $url){
			$pfad = '/' . implode('/',$this->urlInfo( $url )->directorys);
			if($this->isDisallow($pfad,$this->getRobot())){
				array_push($list, $url);
			}
		}
		return $list;
	}
	
}
$r = new robot();

$taj = new sitemap( $r );

var_dump( $taj->proofDisallow() );



?>