<?
class WS_Comment
{
	public function create($login="",$content,$path){
		return Comments::add($path,$content,$login);
	} 

	public function delete($path,$date){
		return Comments::delete($date,$path);		
	} 
	
	public function get($path){
		return Comments::get($path);
	}
}
?>