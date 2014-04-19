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

class AdminThumbs  implements HTMLObject
{

	public function __construct(){
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
		echo "<form id='gthumb-form' class='form-horizontal' action='WS_MgmtFF.mgmt_thumbs' method='post'>\n";
			echo "<legend>".Settings::_("settings","admthumbs")."</legend>\n";
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
