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
class AdminCreate
{
	public function __construct(){
	}
	
	public function toHTML() {
		echo "<div class='row-fluid'>\n
		<form id='createfolder-form' class='form-horizontal' action='WS_MgmtFF.create' method='post'>\n
		<fieldset>\n
		<div class='control-group'>\n
		<label for='foldername' class='control-label'>".Settings::_("adminpanel","name")."</label>\n
		<div class='controls'><input class='input-large'  id='foldername' name='newdir' type='text' value='".Settings::_("adminpanel","new")."'></div>\n
                <input type='hidden' name='path' value=\"".htmlentities(File::a2r(CurrentUser::$path), ENT_QUOTES ,'UTF-8')."\">
		<div class='controls'><label class='checkbox'><input class='input-large'  id='folder_inherit' name='folder_inherit' type='checkbox' value='true' checked=checked />".Settings::_("settings","inherits_folder")."</label></div>\n
		</div>\n
		<div class='controls controls-row'>\n
		<input class='btn btn-primary' type='submit' value='".Settings::_("adminpanel","create")."'>
		</div>\n				
		</fieldset>
		</form>
		</div>";
}
}


?>