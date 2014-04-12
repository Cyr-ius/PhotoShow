<?
class WS_Token
{
	function __construct(){
	}
	
	public function create($variables){
		if (GuestToken::Create($variables['path'],$variables['key']))
			return GuestToken::find_for_path($variables['path']);
			
	} 

	public function delete($variables){
		return GuestToken::delete($variables['key']);	
	} 

	//~ public function exists($token){
		//~ return GuestToken::exist($token);		
	//~ } 
	
	public function get_url($variables){
		return GuestToken::get_url($variables['token']);		
	} 

	public function get_path($variables){
		return GuestToken::get_path($variables['token']);		
	} 
}
?>