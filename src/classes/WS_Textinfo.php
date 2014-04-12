<?
class WS_Textinfo
{
	function __construct(){
	}
	
	public function create($variables){
		return TextInfo::create($variables['path'] ,$variables['title'],$variables['author'],$variables['contain']);
	} 

	public function delete($variables){
		return TextInfo::delete($variables['path']);	
	} 

	public function get($variables){
		return TextInfo::get($variables['path']);	
	} 
}
?>