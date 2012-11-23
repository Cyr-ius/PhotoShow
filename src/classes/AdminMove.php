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
 class AdminMove
 {
 	/// Directories where we can upload
 	public $dirs = array();

 	/// Directories where we can upload
 	public $files = array();

 	/// What have we done ?
 	public $done;

 	/// Currently selected dir/file
 	private $selected;

 	/**
 	 * Create upload page
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function __construct(){

 		/// Get all subdirs
 		$list_dirs = Menu::list_dirs(Settings::$photos_dir,true);

 		/// Get all subfiles
 		$list_files = Menu::list_files(Settings::$photos_dir,true);


 		foreach ($list_dirs as $dir){
 			$d 				= File::a2r($dir);
 			$this->dirs[] 	= $d;
 			$this->files[] 	= $d;
 		}

 		foreach ($list_files as $file){
 			$this->files[] = File::a2r($file);
 		}

 		if(isset(CurrentUser::$path)){
 			$this->selected = File::a2r(CurrentUser::$path);
 		}
 	}

 	/**
 	 * Upload files on the server
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function move(){
 		/// Just to be really sure... 
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){			
			Json::$json = array("action"=>"AdminMove",
							"result"=>1,
							"desc"=>"Error : No Rights",
							"url"=>'.?f='.urlencode(File::a2r(CurrentUser::$path)),
							"js"=>"");						
			return;
 		}

 		$from 	= File::r2a(stripslashes($_POST['pathFrom']));
 		$to  	= File::r2a(stripslashes($_POST['pathTo']));
		$from_thumb = File::r2a(stripslashes(Settings::$thumbs_dir.$_POST['pathFrom']),File::Root());
		$to_thumb = File::r2a(stripslashes(Settings::$thumbs_dir.$_POST['pathTo']),File::Root());
 		$type 	= $_POST['move'];

 		if($from == $to){	
			Json::$json = array("action"=>"AdminMove",
						"result"=>1,
						"desc"=>"Error : Source = Cible",
						"url"=>'.?f='.urlencode(File::a2r(CurrentUser::$path)),
						"js"=>"");					
 			return;
 		}

 		if($type == "rename"){
 			@rename($from,dirname($from)."/".stripslashes($_POST['pathTo']));
 			@rename($from_thumb,dirname($from_thumb)."/".stripslashes($_POST['pathTo']));
			Json::$json = array("action"=>"AdminMove",
						"result"=>0,
						"desc"=>"Rename Successfull ",
						"url"=>'.?f='.urlencode(File::a2r(dirname($from)."/".stripslashes($_POST['pathTo']))),
						"js"=>"");					
			return;
 		}

 		if(is_file($from) || $type=="directory"){
 			@rename($from,$to."/".basename($from));
 			@rename($from_thumb,$to_thumb."/".basename($from_thumb));
			Json::$json = array("action"=>"AdminMove",
						"result"=>1,
						"desc"=>"Rename File or Folder ",
						"url"=>'.?f='.urlencode(File::a2r(CurrentUser::$path)),
						"js"=>"");					
 			return;
 		}

 		/// We are moving multiple files
 		$files = scandir($from);
 		foreach($files as $file){
 			if($file != "." && $file!=".."){
	 			@rename($from."/".$file,$to."/".$file);
	 			@rename($from_thumb."/".$file,$to_thumb."/".$file);
	 		}
 		}
		Json::$json = array("action"=>"AdminMove",
					"result"=>1,
					"desc"=>"Move multiple Files",
					"url"=>'.?f='.urlencode(File::a2r(CurrentUser::$path)),
					"js"=>"");			
		return;
	}

 	/**
 	 * Display upload page on website
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function toHTML(){
 		echo 	"<h1>Move</h1>";
 		echo 	"<form action='?t=Adm&a=Mov' method='post' enctype='multipart/form-data'>";
 		echo 	"<fieldset><span>From</span><div><select name='pathFrom'>";
		foreach($this->files as $file){
 				if($file == $this->selected){
 					$selected = "selected";
 				}else{
 					$selected = "";
 				}
 				echo "<option value='".htmlentities($file, ENT_QUOTES ,'UTF-8')."' $selected>".htmlentities($file, ENT_QUOTES ,'UTF-8')."</option>\n";
 		}

 		echo 	"</select></div></fieldset>\n";

 		echo 	"<fieldset><span>To</span><div><select name='pathTo'>";
 		echo "<option value='.'>.</option>\n";
		foreach($this->dirs as $dir){
 				echo "<option value='".htmlentities($dir, ENT_QUOTES ,'UTF-8')."'>".htmlentities($dir, ENT_QUOTES ,'UTF-8')."</option>\n";
 		}
 		echo 	"</select></div></fieldset>\n";

 		echo 	"In case you want to move a Directory : ";
 		echo 	"<fieldset><span>Move</span><div>";
 		echo 	"<label><input type='radio' name='move' value='directory' checked > Directory </label></br>";
 		echo 	"<label><input type='radio' name='move' value='content'> Contents (and delete directory) </label>";
 		echo 	"</div></fieldset>\n";

 		echo 	"<fieldset><input type='submit' /></fieldset>";
 		echo 	"</form>";

 	}

 }
 ?>