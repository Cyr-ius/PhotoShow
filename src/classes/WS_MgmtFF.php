<?
class WS_MgmtFF
{	
	function __construct(){
	}

	public function create($newfolder,$path,$inherit=null,$public=null,$users=array(),$groups=array()){
		return AdminFiles::create($path,$newfolder,$inherit,$public,$users,$groups);
	} 

	public function delete($path){
		return AdminFiles::delete($path);
	} 
	
	public function list_dirs($dir,$rec=false, $hidden=false) {
		$res = array();
		$m = Menu::list_dirs(File::r2a($dir),$rec, $hidden);
		if(sizeof($m) == 0){
			return $res;
		}
		foreach($m as $i){
			if(Judge::view($i)){
				$res[] = File::a2r($i);
			}
		}
		return $res;
	}
	
	public static function list_files($dir){
		$res = array();
		$m = Menu::list_files(File::r2a($dir));
		if(sizeof($m) == 0){
			return $res;
		}
		foreach($m as $i){
			if(Judge::view($i)){
				$res[] = File::a2r($i);
			}
		}
		return $res;
	}

	public function move($sourcepath,$targetpath){
		return AdminFiles::move($sourcepath,$targetpath,"move");
	} 
	
	public function rename($sourcepath,$targetpath){
		return AdminFiles::move($sourcepath,$targetpath,"rename");
	} 	

	public function mgmt_thumbs($path,$type){
	
		if ($type) {
			if (in_array('clean',$type) || $type=='clean') {
				Settings::cleanthumbs(File::r2a($path));
			}
			if (in_array('create',$type)|| $type=='create') {
				Settings::gener_all(File::r2a($path));
			}
			return true; 
		} else {
			throw new jsonRPCException('Please , select option.');
		}
	} 
	
	public function rotate($path,$orientation=null){

	} 		
	
	public function saveset(){
		$numargs = func_num_args();
		$args = func_get_args();
		$f = fopen(Settings::$admin_settings_file,"w");
		foreach($args as $arg){
			fwrite($f,$arg['name']." = \"".$arg["value"]."\"\n");
		}
		fclose($f);
		Settings::init(true);	
	return true;
	} 




}
?>