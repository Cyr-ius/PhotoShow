<?php
/**
 * This file implements the class BoardHeader.
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
 * BoardHeader
 *
 * Well... It contains the title and some buttons.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class BoardHeader{

	/// Name of the directory listed in parent Board
	public $title;
	
	/// Path of the directory listed in parent Board
	public $path;
	
	/// TestInfo , containing the title and explain
	public $textinfo;	

	/**
	 * Create BoardHeader
	 *
	 * @param string $title 
	 * @author Thibaud Rohmer
	 */
	public function __construct($title,$path){
		$this->path 	=	urlencode(File::a2r($path));
		$this->title 	=	$title;
		$this->textinfo 	=	new TextInfo($path);
		$this->upload	=	new AdminUpload();
	}
	
	/**
	 * Display BoardHeader on Website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		echo "<div class='page-header'>\n";
			/// Title
			if ($this->textinfo->title) { 
				echo 	"<h1 class='album-title'>".$this->textinfo->title."</h1>\n";
			} else { 
				echo 	"<h1 class='album-title'>".(htmlentities($this->title, ENT_QUOTES ,'UTF-8'))."</h1>\n";	
			}
		echo "</div>\n";

		if(CurrentUser::$admin || CurrentUser::$uploader){		
			echo "<div class='row-fluid'>\n";
				$this->upload->toHTML();
			echo "</div>\n";
		}
		echo "<div id='textinfo' class='row-fluid'>\n";
		$this->textinfo->toHTML();
		echo "</div>\n";		
		if(CurrentUser::$admin || CurrentUser::$uploader){
			echo "
			<div class='row-fluid'>
			<div id='view_style' class='btn-group span1' data-toggle='buttons-radio'>\n
				<button id='view-thumb' class='btn btn-mini active' type='button'><i class='icon-th'></i></button>\n
				<button id='view-list' class='btn btn-mini' type='button'><i class='icon-th-list'></i></button>\n
			</div>\n
			</div>\n";			
		}
	}
}

?>