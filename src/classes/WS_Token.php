<?
class WS_Token
{
	function __construct(){
	}
	
	public function create($path, $key = NULL){
		$rslt = GuestToken::Create($path,$key);
		if ($rslt)
			return GuestToken::find_for_path($path);
	} 

	public function delete($token){
		return GuestToken::delete($token);	
	} 

	//~ public function exists($token){
		//~ return GuestToken::exist($token);		
	//~ } 
	
	public function get_url($token){
		return GuestToken::get_url($token);		
	} 

	public function get_path($token){
		return GuestToken::get_path($token);		
	} 
}
?>