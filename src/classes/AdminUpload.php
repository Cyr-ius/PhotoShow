<?php
/**
 * This file implements the class AdminUpload.
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
 * AdminUpload
 *
 * Upload page
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
 class AdminUpload
 {
 	/// Directories where we can upload
 	public $dirs = array();

 	/// What have we done ?
 	public $done;

 	/// Currently selected dir
 	private $selected_dir;

 	/**
 	 * Create upload page
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function __construct(){
 	}

 	/**
 	 * Upload files on the server
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function upload(){
 		
		//$allowedExtensions = array("tiff","jpg","jpeg","gif","png","flv","mov","mpg","mp4","ogv","mts","3gp","webm","avi","wmv","mpeg");

		$already_set_rights = false;
 		/// Just to be really sure... 
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){
			Json::$json = array("action"=>"AdminUpload",
							"result"=>1,
							"desc"=>"Error : No Rights");		
 			return;
 		}
		
 		/// Set upload path
 		$path = stripslashes(File::r2a($_POST['path']));
 		/// Create dir and update upload path if required
 		if(strlen(stripslashes($_POST['newdir']))>0 && !strpos(stripslashes($_POST['newdir']),'..')){

 			$path = $path."/".stripslashes($_POST['newdir']);
 			if(!file_exists($path)){
 				@mkdir($path,0755,true);
 				@mkdir(File::r2a(File::a2r($path),Settings::$thumbs_dir),0755,true);
 			}

 			/// Setup rights
 			if(!isset($_POST['inherit'])){
 				if(isset($_POST['public'])){
 					Judge::edit($path);
 				}else{
 					Judge::edit($path,$_POST['users'],$_POST['groups']);					
 				}
 			}
 			$already_set_rights = true;
 		}
		
		if(!isset($_FILES["file"])) {	
			Json::$json = array("action"=>"AdminUpload",
							"result"=>0,
							"uri"=>urlencode(File::a2r(CurrentUser::$path)),
							"desc"=>"Folder Create");							
 			return;
		}
		

		// Check that file is uploaded and Treat uploaded file
		if ($error == UPLOAD_ERR_OK) {

			// Name of the stored file
			$tmp_name = $_FILES["file"]["tmp_name"];
			// Name on the website
			$name = $_FILES["file"]["name"];
			$info = pathinfo($name);
			$base_name =  basename($name,'.'.$info['extension']);
			// Check filetype
			if(!in_array(strtolower($info['extension']),Settings::$allowedExtensions)){
				continue;
			}
			
			// Rename until this name isn't taken
			$i=1;
			while(file_exists("$path/$name")){
				$name=$base_name."-".$i.".".$info['extension'];
				$i++;
			}

			// Save the files
			if(!move_uploaded_file($tmp_name, "$path/$name")){
				error_log("ERROR/AdminUpload : save uploaded $name refused");
				Json::$json = array("action"=>"AdminUpload",
					"result"=>1,
					"desc"=>"Error : Upload error");
				return;
			}
			// Setup rights
			if(!$already_set_rights && !isset($_POST['inherit'])){
				if(isset($_POST['public'])){
					Judge::edit("$path/$name");
				}else{
					Judge::edit("$path/$name",$_POST['users'],$_POST['groups']);					
				}
			}
			
			Json::$json = array("action"=>"AdminUpload",
				"result"=>0,
				"uri"=>urlencode(File::a2r("$path/$name")),
				"desc"=>"File save :".$name);
				return;
			
		} else { 
				Json::$json = array("action"=>"AdminUpload",
					"result"=>1,
					"desc"=>"Error : Upload error");
				return;
		}
	}

 	/**
 	 * Display upload page on website
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function toHTML(){
		echo "
		<section style='margin-bottom: 5px;'>
			<div id='rights_upload' class='btn-group' data-toggle='buttons-radio'>
				<button type='button' class='btn btn-mini active' value='true'>Inherit</button>
				<button type='button' class='btn btn-mini'>Public</button>
			</div>
			<span ><small>".Settings::_("upload","rights_upload")."</small></span>
		</section>";		
		echo "<div id='dropzone' class='well'>".Settings::_("upload","dropzone")."</div>";
		echo "<div id='uploader'>
				<div id='filelist'> 
				<table class='well table table-striped'>
					<tbody id='files'>
					</tbody>
				</table>
				</div>
			</div>";
 	}
 }
 ?>