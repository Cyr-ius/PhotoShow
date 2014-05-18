<?php
/**
 * This file implements the class AdminFiles.
 * 
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * Admin Files
 *
 * Display the forms for the admin.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class AdminFiles
{

        public function create($path,$newfolder,$inherit=false,$public=null,$users=array(),$groups=array()){
	
 		/// Just to be really sure... 
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){		
                        throw new jsonRPCException('Insufficients rights');
 		}
		$already_set_rights = false;
 		/// Set upload path
 		$path = stripslashes(File::r2a($path));
 		/// Create dir and update upload path if required
 		if(strlen(stripslashes($newfolder))>0 && !strpos(stripslashes($newfolder),'..')){

 			$path = $path."/".stripslashes($newfolder);
 			if(!file_exists($path)){
 				@mkdir($path,0755,true);
 				@mkdir(File::r2a(File::a2r($path),Settings::$thumbs_dir),0755,true);
 			} else {
				throw new jsonRPCException("Folder $newfolder already exists.");
			}

 			/// Setup rights
 			if(!$inherit){
				Judge::edit($path);
 			}
			return array("path"=>urlencode(File::a2r($path)),"rights"=>true);
 		}
        }

 	/**
 	 * Delete files on the server
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function delete($delpath=null){
 		/// Just to be really sure... 
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){
			throw new jsonRPCException('Insufficient rights');
 		}

		if($delpath == Settings::$photos_dir || empty($delpath)){
			throw new jsonRPCException(Settings::$photos_dir." refuse deleted");	
 		}
		
		$delpath = File::r2a($delpath);	
	
		if (is_file(File::path2Thumb($delpath,'small'))) 
			self::rec_del(File::path2Thumb($delpath,'small'));
		self::rec_del(File::path2Thumb($delpath));
		self::rec_del($delpath);
		return array("path"=>urlencode(File::a2r(CurrentUser::$path)));		
	}

 	/**
 	 * Move files on the server
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function move($sourcepath=null,$targetpath=null,$type=null){
 		/// Just to be really sure... 
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){			
			throw new jsonRPCException('Insufficient rights');
 		}
		if (!$sourcepath)
			$sourcepath 	= stripslashes($_POST['pathFrom']);
		if (!$targetpath)
			$targetpath  	= stripslashes($_POST['pathTo']);
		if (!$type)
			$type	=	$_POST['move'];
			
		
		$from 	= File::r2a($sourcepath);	
		$to  	= File::r2a($targetpath)."/".basename($from);	
		$from_thumb = File::path2Thumb($from);
		$to_thumb = File::path2Thumb($to);
		
		
		if (is_file($from)){
			$from_small = File::path2Thumb($from,'small');
			$to_small = File::path2Thumb($to,'small');
		}
		

 		if($from == $to){	
			throw new jsonRPCException('Source and Target are identically');
 		}

 		if($type == "rename"){
			//~ var_dump(dirname($from)."/".$targetpath);
			if(file_exists(dirname($from)."/".$targetpath)){	
				throw new jsonRPCException('A file with the same name already exists');
			}
 			rename($from,dirname($from)."/".$targetpath);
			rename($from_thumb,dirname($from_thumb)."/".$targetpath);
			rename($from_small,dirname($from_small)."/".$targetpath);
			return array("path"=>urlencode(File::a2r(dirname($from)."/".$targetpath)));
 		}
		/// Move File
		rename($from,$to);
		rename($from_thumb,$to_thumb);
		rename($from_small,$to_small);
		return array("path"=>urlencode(File::a2r($to)));
	}

	/**
	 * Reccursively delete all files in $dir
	 * 
	 * @param string $dir
	 * @author Thibaud Rohmer
	 */
	private function rec_del($dir){
		
		if(is_file($dir)){
			return unlink($dir);
		}

		$dirs 	=	Menu::list_dirs($dir);
		$files 	= 	Menu::list_files($dir,false,true);

		foreach($dirs as $d){
			self::rec_del($d);
		}
		
		foreach($files as $f){
			unlink($f);
		}
		return rmdir($dir);
	}

}