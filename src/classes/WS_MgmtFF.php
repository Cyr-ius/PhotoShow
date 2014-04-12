<?
class WS_MgmtFF
{	
	function __construct(){
	}

	public function create($variables){
		return AdminFiles::create($variables['path'],$variables['newfolder'],$variables['inherit']);
	} 

	public function delete($variables){
		return AdminFiles::delete($variables['from']);
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

	public function move($variables){
		return AdminFiles::move($variables['from'],$variables['to'],"move");
	} 
	
	public function rename($variables){
		return AdminFiles::move($variables['pathFrom'],$variables['pathTo'],"rename");
	} 	

	public function mgmt_thumbs($variables){
		if ($variables['type']) {
			if (in_array('clean',$variables['type']) || $variables['type']=='clean') {
				AdminThumbs::cleanthumbs(File::r2a($variables['path']));
			}
			if (in_array('create',$variables['type'])|| $variables['type']=='create') {
				AdminThumbs::gener_all(File::r2a($variables['path']));
			}
			return true; 
		} else {
			throw new jsonRPCException('Please , select option.');
		}
	} 
	
	public function rotate($path,$orientation=null){

	} 		
	
	public function saveset($variables){
		$f = fopen(Settings::$admin_settings_file,"w");
		foreach(array_keys($variables) as $value){
			 fwrite($f,$value." = \"".$variables[$value]."\"\n");
		}		
		fclose($f);
		Settings::init(true);	
	return true;
	} 




}
?>