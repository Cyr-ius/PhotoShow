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

	/// Folders list
	private $folders 			=	array();

	/// Path to localizations
	static private $locpath 	=	array();

	/// Available localizations
	static public $ava_loc 	=	array();
	
	/*** Resize ***/
	static public $upload_resize	=	true;
	static public $upload_crop	=	false;
	static public $upload_preserve_headers	=	true;
	static public $upload_quality	=	90;
	static public $upload_height	=	1200;
	static public $upload_width	=	1920;
	
	/*** Video ***/
	
	///Video encode enable/disable
	static public $encode_video	=	false;
	
	///Video encode type
	static public $encode_type	=	"mp4";
	
	/// FFMPEg path (unix : /usr/bin/ffmpeg or win : c:\ffmpeg.exe)
	static public $ffmpeg_path 		=	"/usr/bin/ffmpeg";
	
	///FFMPEG Option
	static public $ffmpeg_option	=	"-threads 4 -qmax 40 -acodec aac -ab 96k -vcodec libx264 -strict experimental -movflags faststart";	


	/**
	 * Create Settings page
	 * 
	 */
	public function __construct(){
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
		Settings::$admin_settings_file	= 	$config->ps_generated."/Conf/admin_settings.xml";
		Settings::$timezone 			= 	$config->timezone;

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
			$settings_xml = new XMLMg(Settings::$admin_settings_file);
			$admin_settings = $settings_xml->findAll();

			if(isset($admin_settings['name'])){
				Settings::$name			=	stripslashes($admin_settings['name']);
			}

			if(isset($admin_settings['fbappid'])){
				Settings::$fbappid			=	$admin_settings['fbappid'];
			}

			if ($admin_settings['site_address']){
				Settings::$site_address 		=	 $admin_settings['site_address'];
			}else{
				Settings::$site_address		=	 "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
			}

			// Formatting the address so we can directly append "?t=..." to it without worry
			if (!preg_match("/ndex.php$/", Settings::$site_address) && !preg_match("/.*\/$/", Settings::$site_address)){
				Settings::$site_address		=	Settings::$site_address."/";
			}
			
			if(isset($admin_settings['allowedExtImages'])){
				Settings::$allowedExtImages	=	explode(',',$admin_settings['allowedExtImages']);
			}			
			if(isset($admin_settings['allowedExtVideos'])){
				Settings::$allowedExtVideos	=	explode(',',$admin_settings['allowedExtVideos']);
			}
			if(isset($admin_settings['allowedExtFiles'])){
				Settings::$allowedExtFiles		=	explode(',',$admin_settings['allowedExtFiles']);
			}	
			
			Settings::$like 					=	$admin_settings['like'];
			Settings::$plusone 				=	$admin_settings['plusone'];
			Settings::$noregister			=	$admin_settings['noregister'];
			Settings::$forcehttps  			=   	$admin_settings['forcehttps'];
			Settings::$nocomments			=	$admin_settings['nocomments'];
			Settings::$nodownload			=	$admin_settings['nodownload'];
			Settings::$l33t			 		=	$admin_settings['l33t'];
			Settings::$reverse_menu			=	$admin_settings['reverse_menu'];
			Settings::$hide_menu    			=   	$admin_settings['hide_menu'];
			Settings::$thumbs_fixed_width		=	$admin_settings['thumbs_fixed_width'];

			if(isset($admin_settings['icon_path'])){
				Settings::$icon_path			=	$admin_settings['icon_path'];
			}

			if(isset($admin_settings['max_comments'])){
				Settings::$max_comments 	= 	$admin_settings['max_comments'] + 0;
			}

			if(isset($admin_settings['max_img_dir'])){
				Settings::$max_img_dir 		= 	$admin_settings['max_img_dir'] + 0;
			}

			if(isset($admin_settings['loc'])){
				Settings::$loc 				=	 $admin_settings['loc'];
			}
			
			/*** Resize ***/
			Settings::$upload_resize			=	$admin_settings['upload_resize'];
			Settings::$upload_crop			=	$admin_settings['upload_crop'];
			Settings::$upload_preserve_headers	=	$admin_settings['upload_preserve_headers'];
			if(isset($admin_settings['upload_quality'])){
				Settings::$upload_quality		=	$admin_settings['upload_quality'];
			}			
			if(isset($admin_settings['upload_width'])){
				Settings::$upload_width		=	$admin_settings['upload_width'];
			}
			if(isset($admin_settings['upload_height'])){
				Settings::$upload_height		=	$admin_settings['upload_height'];
			}
			
			/*** Video ***/
			Settings::$encode_video			=	$admin_settings['encode_video'];
			if(isset($admin_settings['encode_type'])){
				Settings::$encode_type		=	$admin_settings['encode_type'];
			}			
			if(isset($admin_settings['ffmpeg_path'])){
				Settings::$ffmpeg_path		=	$admin_settings['ffmpeg_path'];
			}
			if(isset($admin_settings['ffmpeg_option'])){
				Settings::$ffmpeg_option		=	$admin_settings['ffmpeg_option'];
			}
		}
		// Create Array Extensions
		Settings::$allowedExtensions 			=	 array_merge(Settings::$allowedExtImages,Settings::$allowedExtVideos,Settings::$allowedExtFiles);			

		// Localization files path
		Settings::$locpath 					=	dirname(dirname(dirname(__FILE__)))."/inc/loc/";

		// Get Localization array
		if(is_file(Settings::$locpath."/".Settings::$loc)){
			Settings::$loc_chosen 			= 	parse_ini_file(Settings::$locpath."/".Settings::$loc,true);
		}

		Settings::$loc_default 				=	parse_ini_file(Settings::$locpath."/default.ini",true);

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
	 * Display settings page
	 */
	public function toHTML(){
	echo "<div class='row-fluid'>\n";
		echo "<div class='well'>\n";
		echo "<form id='setting-form' class='form-horizontal' action='WS_MgmtFF.saveset' method='post'>\n";
		///Global
		echo "<legend>Global</legend>\n";
		echo "<fieldset>\n";
		echo "<div class='control-group'>\n";
		echo "<label for='title' class='control-label'>".Settings::_("settings","title")."</label>\n";
		echo "<div class='controls'><input id='title' class='input-large' type='text' name='name' value='".htmlentities(Settings::$name, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";
		echo "<div class='control-group'>\n";
		echo "<label for='icon_path' class='control-label'>".Settings::_("settings","icon-path")."</label>\n";
		echo "<div class='controls'><input id='icon_path' class='input-large' type='text' name='icon_path' value='".htmlentities(Settings::$icon_path, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";		
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
		echo "<label for='timezone' class='control-label'>".Settings::_("settings","timezone")."</label>";
		echo "<div class='controls'><input id='timezone' class='input-xxlarge' type='text' name='timezone' value='".htmlentities(Settings::$timezone, ENT_QUOTES ,'UTF-8')."' disabled></div>\n";		
		echo "</div>\n";
		echo "</fieldset>\n";
		/// Options
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
		///FaceBook enable
		echo "<legend>Social Networks</legend>\n";
		echo "<fieldset>\n";
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
		echo "</form>\n";			
		echo "</div>\n";
	echo "</div>\n";
		
	}
}
?>
