<?
class WS_Textinfo
{
	function __construct(){
	}
	
	public function create($title=null,$author=null,$contain=null,$path){
		return TextInfo::create($path ,$title,$author,$contain);
	} 

	public function delete($path){
		return TextInfo::delete($path);	
	} 

	public function get($path){
		return TextInfo::get($path);	
	} 
}
?>