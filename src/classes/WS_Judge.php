<?php
class WS_Judge
{

	function __construct(){
	}
		
	public function rights($variables){
		if ($variables['type']=='Pri'){
			return Judge::edit($variables['path'],$variables['users'],$variables['groups'],true);
		}
		if ($variables['type']=='Pub'){
			return Judge::edit($variables['path'],null,null,false);
		}
	} 
}
?>