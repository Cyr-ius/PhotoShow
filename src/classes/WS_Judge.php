<?
class WS_Judge
{
	function __construct(){
	}
		
	public function rights($type,$users=null,$groups=null,$path){
	
		if ($type=='Pri'){

			if (!is_array($users)) {$users=array($users);}
			if (!is_array($groups)) {$groups=array($groups);}
		
			return Judge::edit($path,$users,$groups,true);
		}
		if ($type=='Pub'){
			return Judge::edit($path,null,null,false);
		}
	} 
}
?>