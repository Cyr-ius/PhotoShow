<?
class WS_Group
{
	function __construct(){
	}
		
	public function create($variables){
		return Group::create($variables['groupname'],$variables['rights']);
	} 

	public function delete($variables){
		return Group::delete($variables['name']);	
	} 

	public function exists($variables){
		return Group::exists($variables['groupname']);		
	} 
}
?>