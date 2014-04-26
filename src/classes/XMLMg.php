<?php
class XMLMg 
{	
	//XML Path file
	private $path;
	//XML Node name
	public $key;
	//XML Node value
	public $value;
	
	public function __construct($f=null) {
		$this->path = $f;
	}
	
	public function create($key = NULL,$value=NULL){
		if(!file_exists($this->path) ){
			// Create file
			$xml	=	new SimpleXMLElement('<settings></settings>');
			$xml->asXML($this->path);
		}
		$this->key = $key;
		$this->value = $value;
		$this->save();
		return true;
	}
	public function findAll(){
		$settings	=	array();
		$xml	=	simplexml_load_file($this->path);
		foreach($xml as $ik=>$iv ){
			$this->$ik = $iv;
			$settings[(string) $ik]=(string) $iv;
		}
        return $settings;
	}	
		
	private function save(){
		if (self::exist()){
			self::delete($this->key);
		}
		$xml=simplexml_load_file($this->path);
		$xml->addChild($this->key,$this->value);
		// Saving into file
		$xml->asXML($this->path);
	}	
	
	public function delete($key){
		$i=0;
		$found = false;
		$xml = simplexml_load_file($this->path);
		foreach( $xml as $ik=>$iv ){
			if((string)$ik == $key){
				unset($xml->$ik);
				$found = true;
				break;
			}
		}
		if ($found && $xml->asXML($this->path)){
		    return true;
		} else {
		    return false;
		}
	}	
	
	private function exist(){
	        $xml = simplexml_load_file($this->path);		
		foreach( $xml as $ik=>$iv ){
			if ((string)$ik == (string)$this->key)
				return true;
		}
	return false;
    }
}
?>