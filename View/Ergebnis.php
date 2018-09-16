<?php
namespace View;

/**
 * Blacklist
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 06.10.2014
 */
class Ergebnis {
	
	/**
	 *  Liefert das ergebnislist
	 * @param array $out Das liste
	 * @var array
	 */
	
	public static function ergebnislist($wort, \Model\ErgebnisCollection $liste){
		$out = '';
		foreach($liste AS $ergebnis){
			$out .=	self::render($wort,$ergebnis);
		
		}
		return $out;
	}


    /**
	 *  Setz alle schlusselworter mit Bold format 
	 * setzen auch die element title mit blue farbe und url mit grün farbe 
	 * @param string $wort , array $ergerbnis
	 * @var string ,array
	 */
	
	public static function render($wort,\Model\Ergebnis $ergebnis) {
		$out = '<p><span style="color: blue;">' . self::makeBold($wort, $ergebnis -> getTitle()) . '</span><br />';
		$out .= '<span style="color: green;font-size: .75em;">' . self::makeBold($wort, $ergebnis -> getUrl()) . '</span><br />';
		$out .= self::makeBold($wort, $ergebnis -> getDescription()) . '</p>';
		return $out;
	}


     /**
	 *  Setz alle schlusselworter mit Bold format
	 * @param string $str 
	 * @var string 
	 */
	private static function makeBold($pattern, $subject) {
		$str = preg_replace_callback('/' . $pattern .'/im', function($treffer) {
			return '<b>' . $treffer[0] . '</b>';
		}, $subject);
		return $str;
	}
	
	

}


?>

