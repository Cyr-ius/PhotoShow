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
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright 2011 Thibaud Rohmer + 2013 Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
/**
 * Admin Panel
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright Thibaud Rohmer + Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
<<<<<<< HEAD
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
		$ret =	"<div class='row-fluid'> \n
				<form id='renamefolder-form' class='form-horizontal' action='?f=".urlencode(File::a2r(CurrentUser::$path))."&a=Mov' method='post'>\n
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
				</form>\n
				</div>\n";
		if(CurrentUser::$admin){
			echo $ret;
		}
	}
	
	public function CreateDir_toHTML() {
		$w 	= File::a2r($this->file);
		$ret =	"
				<div class='row-fluid'>\n
				<form id='createfolder-form' class='form-horizontal' action='?a=Upl&f=".urlencode(File::a2r(CurrentUser::$path))."' method='post'>\n
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
				</form>
				</div>";
		if(CurrentUser::$admin){
			echo $ret;
		}
	}
=======
class AdminPanel {
    private $infos;
    private $j;
    private $isfile = false;
    public function __construct() {
        $file = CurrentUser::$path;
        if (!is_array($file) && is_file($file)) {
            $this->isfile = true;
        }
        $this->j = new Judge($file);
        if (is_array($file)) {
            $this->infos = "";
        } else {
            $this->infos = $this->infodirtoHTML($file);
        }
    }
    public function infodirtoHTML($dir) {
        $w = File::a2r($dir);
        $ret = "";
        /// Folder name
        if (strlen($w) > 1) {
            $ret.= "<form class='rename' action='?a=Mov' method='post'>
					<input type='hidden' name='move' value='rename'>
					<input type='hidden' name='pathFrom' value=\"" . htmlentities($w, ENT_QUOTES, 'UTF-8') . "\">
				<fieldset>
					<input type='text' name='pathTo' value=\"" . htmlentities(basename($w), ENT_QUOTES, 'UTF-8') . "\">
					<input type='submit' value='" . Settings::_("adminpanel", "rename") . "'>
				</fieldset>
				</form>";
        }
        $ret.= "<input type='submit' id='multiselectbutton' value='" . Settings::_("adminpanel", "multiselect") . "'>";
        if (!($this->isfile)) {
            $ret.= "<form class='create' action='?a=Upl' method='post'>
					<fieldset>
						<input type='hidden' name='path' value=\"" . htmlentities($w, ENT_QUOTES, 'UTF-8') . "\">
						<input id='foldername' name='newdir' type='text' value='" . Settings::_("adminpanel", "new") . "'>
						<input type='submit' value='" . Settings::_("adminpanel", "create") . "'>
					</fieldset>
					</form>";
            /// Upload Images form
            $ret.= "<div id='files'></div>";
            $w = File::a2r(CurrentUser::$path);
            $ret.= "<form class='dropzone' id=\"" . htmlentities($w, ENT_QUOTES, 'UTF-8') . "\" 
				action='?a=Upl' method='POST' enctype='multipart/form-data'>
				<input type='hidden' name='path' value=\"" . htmlentities($w, ENT_QUOTES, 'UTF-8') . "\">
				<input type='file' name='images[]' multiple >
				<button>Upload</button>
				<div>" . Settings::_("adminpanel", "upload") . "</div>
				</form>";
        }
        return $ret;
    }
    public function toHTML() {
        if (CurrentUser::$uploader) {
            echo $this->infos;
        }
        if (CurrentUser::$admin) {
            echo $this->j->toHTML();
        }
    }
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4
}
?>
