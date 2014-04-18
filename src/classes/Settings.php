<?php
/**
 * This file implements the class Settings.
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
 * Settings
 *
 * Reads all of the settings files and stores them.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class Settings extends Page
{

	/// Directory where the photos are stored
	static public $photos_dir;

	/// Directory where the profil settings are stored
	static public $ps_generated;
	
	/// Directory where the thumbs are stored
	static public $thumbs_dir;
	
	/// Directory where the configuration files are stored
	static public $conf_dir;

	/// File where the admin settings are stored
	static public $admin_settings_file;
	
	static public $timezone;


	/**** Admin Settings ****/

	/// Website name
	static public $name 		=	"PhotoShow";

	/// Website root address
	static public $site_address	=   "";
	
	/// Website icon 
	static public $icon_path	=   "inc/favico.ico";	

	/// Display Facebook button
	static public $like 		=	false;

	/// Facebook app id (optional for facebook button)
	static public $fbappid 		=	"";

	/// Display Google button
	static public $plusone 		=	false;

	/// Remove comments button
	static public $nocomments 	=	false;

	/// Remove registering options
	static public $noregister	=	false;
    
	/// Force https on login/register screens
	static public $forcehttps	    =	false;

	/// Remove download options
	static public $nodownload	=	false;

	/// Max number of comments
	static public $max_comments	=	50;

	/// Max number of comments
	static public $max_img_dir	=	5;

	/// Reverse menu order
	static public $reverse_menu = 	false;
	
	// Hidden Menu
	static public $hide_menu = false;
	
	/// Fixed width for thumbs
	static public $thumbs_fixed_width		=	false;

	/// Selected localization
	static private $loc 			=	"default";

	/// Default localization
	static private $loc_default	=	array();

	/// Localization selected
	static private $loc_chosen 	=	array();

	/// Activate l33t
	static private $l33t 		=	false;
	
	/// Extensions
	static public $allowedExtImages = array("tiff","jpg","jpeg","gif","png");
	static public $allowedExtVideos = array("flv","mov","mpg","mp4","ogv","ogg","mts","3gp","webm","avi","wmv","mpeg");
	static public $allowedExtFiles = array("zip");
	static public $allowedExtensions = array();


	/**** Other ****/

	/// Folders list
	private $folders 			=	array();

	/// Path to localizations
	static private $locpath 	=	array();

	/// Available localizations
	static public $ava_loc 	=	array();
	
	/*** Video ***/
	
	///Video encode enable/disable
	static public $encode_video	=	false;
	
	///Video encode type
	static public $encode_type	=	"mp4";
	
	/// FFMPEg path (unix : /usr/bin/ffmpeg or win : c:\ffmpeg.exe)
	static public $ffmpeg_path 		=	"/usr/bin/ffmpeg";
	
	///FFMPEG Option
	static public $ffmpeg_option	=	"-threads 4 -vcodec libx264 -i_qfactor 0.71 -qmin 10 -qmax 51 -qdiff 4";	


	/**
	 * Create Settings page
	 * 
	 */
	public function __construct(){
		//~ $this->folders = Menu::list_dirs(Settings::$photos_dir,true);
		//~ $allowedExtensions = array_merge($allowedExtImages,$allowedExtVideos,$allowedExtFiles);	
	}

	/**
	 * Read the settings in the files.
	 * If a settings file is missing, raise an exception.
	 *
     * @param string $config_file (for testing purpose only)
	 * @return void
	 * @author Thibaud Rohmer
	 */
	static public function init($forced = false, $config_file = NULL){

		/// Settings already created
		if(Settings::$photos_dir !== NULL && !$forced) return;

		/// Load config.php file 
		if (!isset($config_file)){
		    $config_file		=	realpath(dirname(__FILE__)."/../../config.php");
		}
		if(!include($config_file)){
			throw new Exception("You need to create a configuration file.");
		}

		/// Setup variables
		Settings::$photos_dir		=	$config->photos_dir;
		Settings::$ps_generated		=	$config->ps_generated;
		Settings::$thumbs_dir		=	$config->ps_generated."/Thumbs/";
		Settings::$conf_dir			=	$config->ps_generated."/Conf/";
		Settings::$admin_settings_file	= 	$config->ps_generated."/Conf/admin_settings.ini";
		Settings::$timezone 		= 	$config->timezone;

		/// Set TimeZone
		date_default_timezone_set(Settings::$timezone);

		// Now, check that this stuff exists.
		if(!file_exists(Settings::$photos_dir)){
			if(! @mkdir(Settings::$photos_dir,0755,true)){	
				throw new Exception("PHOTOS dir '".Settings::$photos_dir."' doesn't exist and couldn't be created !");
			}
		}

		if(!file_exists(Settings::$thumbs_dir)){
			if(! @mkdir(Settings::$thumbs_dir,0755,true)){
				throw new Exception("PS_GENERATED dir '".Settings::$thumbs_dir."' doesn't exist or doesn't have the good rights.");
			}
		}

		if(!file_exists(Settings::$conf_dir)){
			if(! @mkdir(Settings::$conf_dir,0755,true)){
				throw new Exception("PS_GENERATED dir '".Settings::$conf_dir."' doesn't exist or doesn't have the good rights.");
			}
		}

		// Get Admin Settings in file
		if(file_exists(Settings::$admin_settings_file)){
			$admin_settings = parse_ini_file(Settings::$admin_settings_file);

			if(isset($admin_settings['name'])){
				Settings::$name			=	stripslashes($admin_settings['name']);
			}

			if(isset($admin_settings['fbappid'])){
				Settings::$fbappid	=	$admin_settings['fbappid'];
			}

			if ($admin_settings['site_address']){
				Settings::$site_address = $admin_settings['site_address'];
			}else{
				Settings::$site_address	= "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
			}

			// Formatting the address so we can directly append "?t=..." to it without worry
			if (!preg_match("/ndex.php$/", Settings::$site_address) && !preg_match("/.*\/$/", Settings::$site_address)){
				Settings::$site_address	=	Settings::$site_address."/";
			}
			
			if(isset($admin_settings['allowedExtImages'])){
				Settings::$allowedExtImages	=	explode(',',$admin_settings['allowedExtImages']);
			}			
			if(isset($admin_settings['allowedExtVideos'])){
				Settings::$allowedExtVideos	=	explode(',',$admin_settings['allowedExtVideos']);
			}
			if(isset($admin_settings['allowedExtFiles'])){
				Settings::$allowedExtFiles	=	explode(',',$admin_settings['allowedExtFiles']);
			}	
			
			Settings::$allowedExtensions = array_merge(Settings::$allowedExtImages,Settings::$allowedExtVideos,Settings::$allowedExtFiles);			
			Settings::$like 			=	isset($admin_settings['like']);
			Settings::$plusone 		=	isset($admin_settings['plusone']);
			Settings::$noregister	=	isset($admin_settings['noregister']);
			Settings::$forcehttps  	=   isset($admin_settings['forcehttps']);
			Settings::$nocomments	=	isset($admin_settings['nocomments']);
			Settings::$nodownload	=	isset($admin_settings['nodownload']);
			Settings::$l33t 		=	isset($admin_settings['l33t']);
			Settings::$reverse_menu=	isset($admin_settings['reverse_menu']);
			Settings::$hide_menu    	=   isset($admin_settings['hide_menu']);
			Settings::$thumbs_fixed_width	=	isset($admin_settings['thumbs_fixed_width']);

			if(isset($admin_settings['icon_path'])){
				Settings::$icon_path	=	$admin_settings['icon_path'];
			}

			if(isset($admin_settings['max_comments'])){
				Settings::$max_comments = 	$admin_settings['max_comments'] + 0;
			}

			if(isset($admin_settings['max_img_dir'])){
				Settings::$max_img_dir = 	$admin_settings['max_img_dir'] + 0;
			}

			if(isset($admin_settings['loc'])){
				Settings::$loc = $admin_settings['loc'];
			}
			
			/*** Video ***/
			Settings::$encode_video	=	isset($admin_settings['encode_video']);
			if(isset($admin_settings['encode_type'])){
				Settings::$encode_type	=	$admin_settings['encode_type'];
			}			
			if(isset($admin_settings['ffmpeg_path'])){
				Settings::$ffmpeg_path	=	$admin_settings['ffmpeg_path'];
			}
			if(isset($admin_settings['ffmpeg_option'])){
				Settings::$ffmpeg_option	=	$admin_settings['ffmpeg_option'];
			}
		}
		// Create Array Extensions
		Settings::$allowedExtensions = array_merge(Settings::$allowedExtImages,Settings::$allowedExtVideos,Settings::$allowedExtFiles);			

		// Localization files path
		Settings::$locpath = dirname(dirname(dirname(__FILE__)))."/inc/loc/";

		// Get Localization array
		if(is_file(Settings::$locpath."/".Settings::$loc)){
			Settings::$loc_chosen = parse_ini_file(Settings::$locpath."/".Settings::$loc,true);
		}

		Settings::$loc_default = parse_ini_file(Settings::$locpath."/default.ini",true);

		// Localization files available
		Settings::$ava_loc=array();
		$a = scandir(Settings::$locpath);
		foreach($a as $f){
			if(File::Extension($f) == "ini"){
				Settings::$ava_loc[]=$f;
			}
		}
	}

	/**
	 * Set website language
	 */
	static public function set_lang($l){
		// Get Localization array
		if(is_file(Settings::$locpath."/".$l.".ini")){
			Settings::$loc_chosen = parse_ini_file(Settings::$locpath."/".$l.".ini",true);
		}
	}

	/**
	 * Returns value of $t in selected language
	 * 
	 */
	static public function _($a,$t){
		if(isset(Settings::$loc_chosen[$a][$t])){
			$t = Settings::$loc_chosen[$a][$t];
		}else if(isset(Settings::$loc_default[$a][$t])){
			$t = Settings::$loc_default[$a][$t];
		}

		if(Settings::$l33t){
			$t = Settings::l33t($t);
		}

		return $t;
	}
	
	static public function toRegexp($i) {
		return "!" . $i . "!";
	}

	static public function l33t($t){
		$t 		= strtolower($t);
		$from 	= array("a", "e", "f", "g","l", "o", "s", "t","h", "c", "m","n", "r", "v", "w");
		$to 	= array("4", "3", "ph", "9","1", "0", "5",  "7",'|-|', '(', '|\/|','|\|', '|2', '\/', '\/\/');
    	
    	return preg_replace(array_map(array(Settings,toRegexp), $from), $to, $t);
	}

	/**
	 * Save new settings
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function set(){
        $var = array("name",
            "site_address",
	    "icon_path",
            "like",
            "plusone",
            "fbappid",
            "max_comments",
            "noregister",
            "forcehttps",
            "nocomments",
            "nodownload",
            "max_img_dir",
	    "thumbs_fixed_width",
            "loc",
            "l33t",
	    "allowedExtImages",
	    "allowedExtVideos",
	    "allowedExtFiles",
            "reverse_menu",
            "hide_menu",
	    "encode_video",
	    "encode_type",
	    "ffmpeg_path",
	    "ffmpeg_option"
	    );
		$f = fopen(Settings::$admin_settings_file,"w");

		foreach($var as $v){
			if(isset($_POST["$v"])){
				fwrite($f,"$v = \"".$_POST["$v"]."\"\n");
			}
		}
		fclose($f);
		Settings::init(true);					
	}

	/**
	 * Generate thumbs and webimages reccursively inside a folder
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function gener_all($folder){
		$files = Menu::list_files($folder,true);

		if( !ini_get('safe_mode') ){ 
			set_time_limit(1200); 
		}

		foreach($files as $file){
			/// Generate thumb
			Provider::image($file,true,false,false);

			/// Generate webimg
			Provider::image($file,false,false,false);
		}
		return;
	}
	
	
	public static function cleanthumbs($folder){
		$files = Menu::list_files(Settings::$thumbs_dir.File::a2r($folder),true);

		if( !ini_get('safe_mode') ){ 
			set_time_limit(1200); 
		}

		foreach($files as $file){
			@unlink($file);
		}	
		return;
	}	

	/**
	 * Display settings page
	 */
	public function toHTML(){

	echo "<div class='row-fluid'>\n";
		echo "<div class='well'>\n";
		echo "<form id='setting-form' class='form-horizontal' action='WS_MgmtFF.saveset' method='post'>\n";
		echo "<legend>Global</legend>\n";
		echo "<fieldset>\n";
		/// Site Title
		echo "<div class='control-group'>\n";
		echo "<label for='title' class='control-label'>".Settings::_("settings","title")."</label>\n";
		echo "<div class='controls'><input id='title' class='input-large' type='text' name='name' value='".htmlentities(Settings::$name, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";
		/// Icon Site
		echo "<div class='control-group'>\n";
		echo "<label for='icon_path' class='control-label'>".Settings::_("settings","icon-path")."</label>\n";
		echo "<div class='controls'><input id='icon_path' class='input-large' type='text' name='icon_path' value='".htmlentities(Settings::$icon_path, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";		
		/// Language
		echo "<div class='control-group'>\n";
		echo "<label for='language' class='control-label'>".Settings::_("settings","language")."</label>\n";
		echo "<div class='controls'>\n";
		echo "<select name='loc'>\n";
		foreach(Settings::$ava_loc as $l){
			$p = htmlentities($l, ENT_QUOTES ,'UTF-8');
			echo "<option value=\"".addslashes($p)."\"";
			if($p == Settings::$loc){
				echo " selected ";
			}
			echo ">".substr($p,0,-4)."</option>\n";
		}
		echo "</select>\n";
		echo "</div>\n";
		echo "</div>\n";		
		/// Site Address
		echo "<div class='control-group'>\n";
		echo "<label for='site_address' class='control-label'>".Settings::_("settings","site_address")."</label>";
		echo "<div class='controls'><input id='site_address' class='input-large' type='text' name='site_address' value='".htmlentities(Settings::$site_address, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";		
		echo "</fieldset>\n";	

		///Infos
		echo "<legend>Infos</legend>\n";
		echo "<fieldset>\n";
		echo "<div class='control-group'>\n";
		echo "<label for='photos_dir' class='control-label'>".Settings::_("settings","photos_dir")."</label>";
		echo "<div class='controls'><input id='photos_dir' class='input-xxlarge' type='text' name='photos_dir' value='".htmlentities(Settings::$photos_dir, ENT_QUOTES ,'UTF-8')."' disabled></div>\n";		

		echo "<label for='ps_generated' class='control-label'>".Settings::_("settings","ps_generated")."</label>";
		echo "<div class='controls'><input id='ps_generated' class='input-xxlarge' type='text' name='ps_generated' value='".htmlentities(Settings::$ps_generated, ENT_QUOTES ,'UTF-8')."' disabled></div>\n";		

		//~ echo "<label for='thumbs_dir' class='control-label'>".Settings::_("settings","thumbs_dir")."</label>";
		//~ echo "<div class='controls'><input id='thumbs_dir' class='input-xxlarge' type='text' name='thumbs_dir' value='".htmlentities(Settings::$thumbs_dir, ENT_QUOTES ,'UTF-8')."'></div>\n";		

		//~ echo "<label for='conf_dir' class='control-label'>".Settings::_("settings","conf_dir")."</label>";
		//~ echo "<div class='controls'><input id='conf_dir' class='input-xxlarge' type='text' name='conf_dir' value='".htmlentities(Settings::$conf_dir, ENT_QUOTES ,'UTF-8')."'></div>\n";		

		//~ echo "<label for='admin_settings_file' class='control-label'>".Settings::_("settings","admin_settings_file")."</label>";
		//~ echo "<div class='controls'><input id='admin_settings_file' class='input-xxlarge' type='text' name='admin_settings_file' value='".htmlentities(Settings::$admin_settings_file, ENT_QUOTES ,'UTF-8')."'></div>\n";		

		echo "<label for='timezone' class='control-label'>".Settings::_("settings","timezone")."</label>";
		echo "<div class='controls'><input id='timezone' class='input-xxlarge' type='text' name='timezone' value='".htmlentities(Settings::$timezone, ENT_QUOTES ,'UTF-8')."' disabled></div>\n";		


		echo "</div>\n";
		echo "</fieldset>\n";

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
		echo "<legend>Options</legend>\n";
		echo "<fieldset>\n";
		echo "<div class='control-group'>\n";
		echo "<label class='checkbox'>\n";
		if(Settings::$noregister){echo "<input type='checkbox' name='noregister' checked>";}else{echo "<input type='checkbox' name='noregister'>";}		
		echo Settings::_("settings","noregister")."</label>\n";
		echo "<label class='checkbox'>\n";
		if(Settings::$forcehttps){echo "<input type='checkbox' name='forcehttps' checked>";}else{echo "<input type='checkbox' name='forcehttps'>";}		
		echo Settings::_("settings","forcehttps")."</label>\n";	
		echo "<label class='checkbox'>\n";
		if(Settings::$nocomments){echo "<input type='checkbox' name='nocomments' checked>";}else{echo "<input type='checkbox' name='nocomments'>";}		
		echo Settings::_("settings","nocomment")."</label>\n";				
		echo "<label class='checkbox'>\n";
		if(Settings::$nodownload){echo "<input type='checkbox' name='nodownload' checked>";}else{echo "<input type='checkbox' name='nodownload'>";}		
		echo Settings::_("settings","nodownload")."</label>\n";		
		echo "<label  class='checkbox'>\n";
		if(Settings::$reverse_menu){echo "<input type='checkbox' name='reverse_menu' checked>";}else{echo "<input type='checkbox' name='reverse_menu'>";}		
		echo Settings::_("settings","reverse_menu")."</label>\n";				
		echo "<label  class='checkbox'>";
		if(Settings::$hide_menu){echo "<input type='checkbox' name='hide_menu' checked>";}else{echo "<input type='checkbox' name='hide_menu'>";}		
		echo Settings::_("settings","hide_menu")."</label>\n";		
		echo "<label  class='checkbox'>";
		if(Settings::$thumbs_fixed_width){echo "<input type='checkbox' name='thumbs_fixed_width' checked>";}else{echo "<input type='checkbox' name='thumbs_fixed_width'>";}		
		echo Settings::_("settings","thfixedwidth")."</label>\n";	
		echo "<label  class='checkbox'>\n";
		if(Settings::$l33t){echo "<input type='checkbox' name='l33t' checked>";}else{echo "<input type='checkbox' name='l33t'>";}		
		echo Settings::_("settings","l33t")."</label>\n";	
		echo "</div>\n";		
		/// Max Comments
		echo "<div class='control-group'>\n";		
		echo "<label for='numcomments' class='control-label'>".Settings::_("settings","numcomments")."</label>";
		echo "<div class='controls'><input id='numcomments' class='input-large' type='text' name='max_comments' value='".htmlentities(Settings::$max_comments, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";
		/// Max Img Dir
		echo "<div class='control-group'>\n";		
		echo "<label for='sens' class='control-label'>".Settings::_("settings","sens")."</label>";
		echo "<div class='controls'><input id='sens' class='input-large' type='text' name='max_img_dir' value='".htmlentities(Settings::$max_img_dir, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";		
		echo "</fieldset>\n";		

		echo "<legend>Social Networks</legend>\n";
		echo "<fieldset>\n";
		///FaceBook enable
		echo "<div class='control-group'>\n";
		echo "<label class='checkbox'>\n";
		if(Settings::$like){echo "<input type='checkbox' name='like' checked>";}else{echo "<input type='checkbox' name='like'>";}		
		echo Settings::_("settings","fb")."</label>\n";
		echo "</div>\n";
		/// Facebook App ID
		echo "<div class='control-group'>\n";		
		echo "<label for='fbappid' class='control-label'>".Settings::_("settings","facebook_appid")."</label>";
		echo "<div class='controls'><input id='fbappid' class='input-large' type='text' name='fbappid' value='".htmlentities(Settings::$fbappid, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";				
		echo "</fieldset>\n";		
		
		echo "<legend>Video</legend>\n";
		echo "<fieldset>\n";
		/// Encode Video
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

		echo "<h3>".Settings::_("settings","admthumbs")."</h3>\n";
		echo "<div class='well'>\n";
		echo "<form id='gthumb-form' class='form-horizontal' action='WS_MgmtFF.mgmt_thumbs' method='post'>\n";
			echo "<fieldset>\n";
				echo "<div class='control-group'>\n";		
					echo "<label for='ffmpeg_path' class='control-label'>".Settings::_("settings","folder")."</label>";
					echo "<div class='controls'>";
						echo "<select name='path' class='input-xxlarge'>";
						echo "<option value='.'>".Settings::_("settings","all")."</option>";
							foreach(Menu::list_dirs(Settings::$photos_dir,true) as $f){
								$p = htmlentities(File::a2r($f), ENT_QUOTES ,'UTF-8');
								echo "<option value=\"".addslashes($p)."\">".basename($p)."</option>";
							}
						echo "</select>";		
					echo "</div>\n";
				echo "</div>\n";
				echo "<div class='control-group'>\n";
					echo "<label class='checkbox'><input type='checkbox' name='type[]' value='clean'>".Settings::_("settings","delthumb")."</label>\n";
					echo "<label class='checkbox'><input type='checkbox' name='type[]' value='create'>".Settings::_("settings","genthumb")."</label>\n";

				echo "</div>\n";
				echo "<div class='controls controls-row'>\n";
					echo "<input class='btn btn-primary' type='submit' value='".Settings::_("settings","submit")."' data-loading-text='Generating...'>\n";
				echo "</div>\n";		
			echo "</fieldset>\n";
		echo "</form>";		
		echo "</div>";
	echo "</div>\n";
		
	}
}
?>
