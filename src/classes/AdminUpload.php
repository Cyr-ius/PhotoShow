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
 class AdminUpload  implements HTMLObject
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
 		
 		/// Just to be really sure... 
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){
			throw new jsonRPCException('Insufficients rights for Upload');
 		}

 		/// Set upload path
 		$path = stripslashes(File::r2a($_POST['path']));

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
				error_log("ERROR/AdminUpload : Extension $name refused");
				throw new jsonRPCException('Error : Upload error , extension refused');
			}
			
			// Rename until this name isn't taken
			$i=1;
			while(file_exists("$path/$name")){
				$name=$base_name."-".$i.".".$info['extension'];
				$i++;
			}
                        
			// Save the files
			if(!move_uploaded_file($tmp_name, "$path/$name")){
				error_log("ERROR/AdminUpload : Error to save a $path/$name");
				throw new jsonRPCException("Error to save a $path/$name");
			}
			
			if (strtolower($info['extension'])=='zip') {
				$rslt = Provider::unzip("$path/$name");
				die(json_encode(array('jsonrpc'=>'2.0','result'=>array("path"=>$rslt,"type"=>"zip"),'id'=>0)));
			}
			
                        $img_path = File::a2r($path.'/'.$name);
			// Setup rights
			if(!isset($_POST['inherit'])){
				if(isset($_POST['public'])){
					Judge::edit("$path/$name");
				}else{
					Judge::edit("$path/$name",$_POST['users'],$_POST['groups']);					
				}
			}
                        //~ error_log("SUCCES/AdminUpload : $path/$name");
                        die(json_encode(array('jsonrpc'=>'2.0','result'=>array("path"=>urlencode($img_path),"type"=>File::Type($img_path)),'id'=>0)));
			
		} else { 
			throw new jsonRPCException("Failed to move uploaded file");
		}
	}

 	/**
 	 * Display upload page on website
 	 * 
 	 * @author Thibaud Rohmer
 	 */
         public function toHTML(){
         
         echo "<div class='row-fluid'>\n";
		echo "<div class='well'>\n";
		echo "<form id='setting-form' class='form-horizontal' action='WS_MgmtFF.saveset' method='post'>\n";
		///Extensions
		echo "<legend>Extensions</legend>\n";
		echo "<fieldset>\n";
		echo "<div class='control-group'>\n";
		echo "<label for='allowedExtImages' class='control-label'>".Settings::_("settings","extImages")."</label>";
		echo "<div class='controls'><input id='allowedExtImages' class='input-xxlarge' type='text' name='allowedExtImages' value='".htmlentities(implode(',',Settings::$allowedExtImages), ENT_QUOTES ,'UTF-8')."'></div>\n";		
		echo "<label for='allowedExtVideos' class='control-label'>".Settings::_("settings","extVideos")."</label>";
		echo "<div class='controls'><input id='allowedExtVideos' class='input-xxlarge' type='text' name='allowedExtVideos' value='".htmlentities(implode(',',Settings::$allowedExtVideos), ENT_QUOTES ,'UTF-8')."'></div>\n";		
		echo "<label for='allowedExtFiles' class='control-label'>".Settings::_("settings","extFiles")."</label>";
		echo "<div class='controls'><input id='allowedExtFiles' class='input-xxlarge' type='text' name='allowedExtFiles' value='".htmlentities(implode(',',Settings::$allowedExtFiles), ENT_QUOTES ,'UTF-8')."'></div>\n";		
		echo "</div>\n";
		echo "</fieldset>\n";
		///Upload resizing
		echo "<legend>Upload resizing</legend>\n";
		echo "<fieldset>\n";
		echo "<div class='control-group'>\n";
		echo "<label class='checkbox'>";
		if(Settings::$upload_resize){echo "<input type='checkbox' name='upload_resize' checked>";}else{echo "<input type='checkbox' name='upload_resize'>";}		
		echo Settings::_("settings","upload_resize")."</label>\n";
		echo "</div>\n";     
		echo "<div class='control-group offset1'>\n";
		echo "<label class='checkbox'>";
		if(Settings::$upload_crop){echo "<input type='checkbox' name='upload_crop' checked>";}else{echo "<input type='checkbox' name='upload_crop'>";}		
		echo Settings::_("settings","upload_crop")."</label>\n";
		echo "</div>\n";   
		echo "<div class='control-group offset1'>\n";
		echo "<label class='checkbox'>";
		if(Settings::$upload_preserve_headers){echo "<input type='checkbox' name='upload_preserve_headers' checked>";}else{echo "<input type='checkbox' name='upload_preserve_headers'>";}		
		echo Settings::_("settings","upload_preserve_headers")."</label>\n";
		echo "</div>\n";                         
		echo "<div class='control-group'>\n";
		echo "<label for='upload_quality' class='control-label'>".Settings::_("settings","upload_quality")."</label>";
		echo "<div class='controls'><input id='upload_quality' class='input-xxlarge' type='text' name='upload_quality' value='".htmlentities(Settings::$upload_quality, ENT_QUOTES ,'UTF-8')."'></div>\n";		
		echo "<label for='upload_height' class='control-label'>".Settings::_("settings","upload_height")."</label>";
		echo "<div class='controls'><input id='upload_height' class='input-xxlarge' type='text' name='upload_height' value='".htmlentities(Settings::$upload_height, ENT_QUOTES ,'UTF-8')."'></div>\n";		
		echo "<label for='upload_width' class='control-label'>".Settings::_("settings","upload_width")."</label>";
		echo "<div class='controls'><input id='upload_width' class='input-xxlarge' type='text' name='upload_width' value='".htmlentities(Settings::$upload_width, ENT_QUOTES ,'UTF-8')."'></div>\n";		
		echo "</div>\n";
		echo "</fieldset>\n";                
		/// Encode Video
		echo "<legend>Video</legend>\n";
		echo "<fieldset>\n";
		echo "<div class='control-group'>\n";
		echo "<label class='checkbox'>";
		if(Settings::$encode_video){echo "<input type='checkbox' name='encode_video' checked>";}else{echo "<input type='checkbox' name='encode_video'>";}		
		echo Settings::_("settings","video_comment")."</label>\n";
		echo "</div>\n";
                       /// FFmpeg Type
		echo "<div class='control-group'>\n";		
		echo "<label for='encode_type' class='control-label'>".Settings::_("settings","encode_type")."</label>";
		echo "<div class='controls'>";
			echo "<select id='encode_type' name='encode_type' class='input-xxlarge'>\n";
				if (Settings::$encode_type=='mp4') {echo "<option value='mp4' selected>mp4</option>\n"; } else {echo "<option value='mp4'>mp4</option>\n";}
				if (Settings::$encode_type=='ogg') {echo "<option value='ogg' selected>ogg</option>\n"; } else {echo "<option value='ogg'>ogg</option>\n";}
				if (Settings::$encode_type=='webm') {echo "<option value='webm' selected>webm</option>\n"; } else {echo "<option value='webm'>webm</option>\n";}
			echo "</select>\n";
		echo "</div>\n";
		echo "</div>\n";	
                       /// FFmpeg Path
		echo "<div class='control-group'>\n";		
		echo "<label for='ffmpeg_path' class='control-label'>".Settings::_("settings","ffmpeg_path")."</label>";
		echo "<div class='controls'><input id='ffmpeg_path' class='input-xxlarge' type='text' name='ffmpeg_path' value='".htmlentities(Settings::$ffmpeg_path, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";	
                        /// FFmpeg command line
		echo "<div class='control-group'>\n";		
		echo "<label for='ffmpeg_option' class='control-label'>".Settings::_("settings","ffmpeg_option")."</label>";
		echo "<div class='controls'><input id='ffmpeg_option' class='input-xxlarge' type='text' name='ffmpeg_option' value='".htmlentities(Settings::$ffmpeg_option, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";	
		echo "</fieldset>\n";		
		echo "</form>\n";			
		echo "</div>\n";
	echo "</div>\n";
         }
         
 	public function DropZone(){
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