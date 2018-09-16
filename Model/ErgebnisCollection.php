<?php
namespace Model;

/**
 * Blacklist
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 06.10.2014
 */
class ErgebnisCollection implements \Iterator{
	/**
	 * Liste inhalt alle content von title description un url
	 * @var array
	 */
    protected $liste = array();
	
	/**
	 * Fügt die Elemente von title ,description, link in der Liste
	 * @param asdfasdf 
	 */
	public function add($title, $description, $url){
		$this->addItem(new \Model\Ergebnis($title,$description,$url));
	}
	/**
	 * Fügt die Elemente von title ,description, link in der Liste
	 * @var array
	 */
	public function addItem( $item){
		array_push($this->liste, $item);
		
	}
	/**
	 *  Setzt den Dateizeiger auf das erste Byte die array liste
	 * @var array
	 */
	public function rewind() {
        reset($this->liste);
    }
    /**
	 *  Liefert das aktuelle Element von die array liste
	 * @var array
	 */
 
    public function current() {
        return current($this->liste);
    }
   /**
	 *  Liefert einen Schlüssel eines assoziativen von die array liste
	 * @return int
	 */
    public function key() {
        return key($this->liste);
    }
   /**
	 *  Rückt den internen Zeiger von die array liste vor
	 * @var array
	 */
    public function next() {
        return next($this->liste);
    }
   /**
	 *  Prüft, ob die aktuelle Position zulässig ist
	 * @var array
	 */
    public function valid() {
       return $this->current()!==FALSE;
    }
	
	public function __construct(){
		// $this->add($title, $description, $url);
		// $this->addItem($item);
		echo 'Aloha';
	}
	
	
}
?>