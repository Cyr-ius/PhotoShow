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

        public function create($path,$newfolder,$inherit=null,$public=null,$users=array(),$groups=array()){
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
 			if(!isset($inherit)){
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
		$delpath = File::r2a($delpath);
		if (!$delpath) {
			$delpath 	=	File::r2a(stripslashes($_POST['del']));
		}
		
		if($delpath == Settings::$photos_dir || empty($delpath)){
			throw new jsonRPCException(Settings::$photos_dir." refuse deleted");	
 		}

		$file_file	       = new File($delpath);
		$thumb_path_no_ext = File::r2a(Settings::$thumbs_dir.dirname(File::a2r($delpath))."/".$file_file->name,File::Root());

		switch($file_file->type){
			case "Image":
				$del_thumb_small   = $thumb_path_no_ext.'_small.'.$file_file->extension;	
				$del_thumb    = $thumb_path_no_ext.'.'.$file_file->extension;
			case "Video":
				$del_thumb_small   = $thumb_path_no_ext.'.mp4';	
				$del_thumb    = $thumb_path_no_ext.'.jpg';
		}			
		
		
		if(is_file($del_thumb)){
		self::rec_del($del_thumb);
		}
		if(is_file($del_thumb_small)){
		self::rec_del($del_thumb_small);
		}
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
		$to  	= File::r2a($targetpath);		
		$from_thumb = File::r2a(stripslashes(Settings::$thumbs_dir.$sourcepath),File::Root());
		$to_thumb = File::r2a(stripslashes(Settings::$thumbs_dir.$targetpath),File::Root());

 		if($from == $to){	
			throw new jsonRPCException('Source and Target are identically');
 		}

 		if($type == "rename"){
 			rename($from,dirname($from)."/".$targetpath);
 			rename($from_thumb,dirname($from_thumb)."/".$targetpath);
			return array("path"=>urlencode(File::a2r(dirname($from)."/".$targetpath)));
 		}
				
		/// Move File
		rename($from,$to."/".basename($from));
		rename($from_thumb,$to_thumb."/".basename($from_thumb));					
		return array("path"=>urlencode(File::a2r($to."/".basename($from))));
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