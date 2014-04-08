<?
class WS_Group
{
	function __construct(){
	}
		
	public function create($name,$rights=array()){
		return Group::create($name,$rights);
	} 

	public function delete($name){
		return Group::delete($name);	
	} 

	public function exists($name){
		return Group::exists($name);		
	} 
}
?>