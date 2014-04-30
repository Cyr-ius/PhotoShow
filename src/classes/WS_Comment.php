<?php
class WS_Comment
{
	public function create($variables){
		return Comments::add($variables['path'],$variables['content'],$variables['login']);
	} 

	public function delete($variables){
		return Comments::delete($variables['date'],$variables['path']);		
	} 
	
	public function get($variables){
		return Comments::get($variables['path']);
	}
}
?>