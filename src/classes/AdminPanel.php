<?php
/**
 * This file implements the class AdminPanel.
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
 * Admin Panel
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class AdminPanel
{

	private $infos;

	private $j;

	private $isfile = false;

	public function __construct(){

		$file = CurrentUser::$path;
		$this->file = $file;
		if(is_file($this->file)){
			$this->isfile = true;
		}
		$this->j = new Judge($this->file);
	}
	
	public function RenameDir_toHTML(){
		$w 	= File::a2r($this->file);
		/// Folder name
		$ret =	"<form id='renamefolder-form' class='form-horizontal' action='?f=".urlencode(File::a2r(CurrentUser::$path))."&a=Mov' method='post'>\n
				<fieldset>\n
				<div class='control-group'>\n
				<label for='folderrename' class='control-label'>".Settings::_("adminpanel","name")."</label>\n
				<div class='controls'><input  id='folderrename' class='input-large' type='text' name='pathTo' value=\"".htmlentities(basename($w), ENT_QUOTES ,'UTF-8')."\"></div>\n
				</div>\n
				<div class='controls controls-row'>\n
				<input class='btn btn-primary' type='submit' value='".Settings::_("adminpanel","rename")."'>
				</div>\n					
				<input type='hidden' name='move' value='rename'>
				<input type='hidden' name='pathFrom' value=\"".htmlentities($w, ENT_QUOTES ,'UTF-8')."\">	
				</fieldset>\n
				</form>\n";
		if(CurrentUser::$admin){
			echo $ret;
		}
	}
	
	public function CreateDir_toHTML() {
		$w 	= File::a2r($this->file);
		$ret =	"<form id='createfolder-form' class='form-horizontal' action='?a=Upl&f=".urlencode(File::a2r(CurrentUser::$path))."' method='post'>\n
				<fieldset>\n
				<div class='control-group'>\n
				<label for='foldername' class='control-label'>".Settings::_("adminpanel","name")."</label>\n
				<div class='controls'><input class='input-large'  id='foldername' name='newdir' type='text' value='".Settings::_("adminpanel","new")."'></div>\n
				</div>\n
				<div class='controls controls-row'>\n
				<input class='btn btn-primary' type='submit' value='".Settings::_("adminpanel","create")."'>
				</div>\n				
				<input type='hidden' name='path' value=\"".htmlentities($w, ENT_QUOTES ,'UTF-8')."\">
				</fieldset>
				</form>";
		if(CurrentUser::$admin){
			echo $ret;
		}
	}
}


?>