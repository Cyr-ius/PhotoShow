<?php
/**
 * This file implements the class Board.
 * 
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package	   PhotoShow
 * @author	   Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright  2011 Thibaud Rohmer
 * @license	   http://www.gnu.org/licenses/
 * @link	   http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * Board
 *
 * Lists the content of a directory and displays
 * it on a grid.
 * It implements a grid generating algorithm, and
 * outputs its content in a div of class board
 * via the toHTML() function.
 *
 * @package	   PhotoShow
 * @author	   Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright  Thibaud Rohmer
 * @license	   http://www.gnu.org/licenses/
 * @link	   http://github.com/thibaud-rohmer/PhotoShow
 */
class GridDir implements HTMLObject
{
	/// Board title : name of the directory listed
	private $title;
	
	/// Path to listed directory
	private $path;
	
	/// Paths to the directories in the directory
	private $dirs;
	
	/// Array of each line of the grid
	private $boardlines=array();

	/// Array of the folders
	private $boardfolders=array();
	
	/// Comments
	public $num;

	/**
	 * Board constructor
	 *
	 * @param string $path 
	 * @author Thibaud Rohmer
	 */
	public function __construct($path=NULL){
		
		if(!isset($path)){
			$path = CurrentUser::$path;
		}

		$this->path	=	$path;
		$this->title	=	basename($this->path);
		$this->dirs	=	Menu::list_dirs($this->path);
		$this->num	=	count(dirname($this->dirs));
	}
	
	/**
	 * Display board on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){	

		//Display album
		$this->foldergrid();
		if(sizeof($this->boardfolders)>0){
			echo "<div class='well albums'>\n";
			echo "<legend><h4>".Settings::_("board","albums")."</h4></legend>\n";
			echo "<ul class='thumbs'>\n";
			foreach($this->boardfolders as $boardfolder){
				$boardfolder->toHTML();
			}
			echo "</ul>\n";
			echo "</div>\n";
		} else {
			echo "<div class='well albums'>\n";
			echo "<legend><h4>".Settings::_("board","albums")."</h4></legend>\n";
			echo "<ul class='thumbs'>\n";
			echo "</ul>\n";
			echo "</div>\n";		
		
		}
		
	}
	
	/**
	 * Generate a foldergrid
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	private function foldergrid(){
		foreach($this->dirs as $d){
			
			$firstImg = Judge::searchDir($d);
			if(!(Judge::view($d) || $firstImg)){
				continue;
			}

			$f = Menu::list_files($d,true);
						
			if( CurrentUser::$admin || CurrentUser::$uploader || sizeof($f) > 0){
				if($firstImg){
					$f[0] = $firstImg;
				}
				$item = new BoardDir($d,$f);
				$this->boardfolders[] = $item;
			}
		}
		if(Settings::$reverse_menu){
			$this->boardfolders = array_reverse($this->boardfolders);
		}
	}
}
