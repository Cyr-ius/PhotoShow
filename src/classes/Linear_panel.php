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
class Linear_panel implements HTMLObject
{
	/// Board title : name of the directory listed
	private $title;
    
	/// Path to listed directory
	private $path;
	
	/// Paths to the files in the directory
	private $files;
	

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
		$this->path=$path;

		// If $path is a file, list directory containing the file
		if(is_file($path)){
			$this->path		=	dirname($path);
		}
		$this->files	=	Menu::list_files($this->path);
	}
	
	/**
	 * Display Linear_panel on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){		

		$this->grid = $this->linear_grid();
		foreach($this->boardlines as $boardline){
			$boardline->toHTML();
		}
	}
	
	/**
	 * Generate Public grid, line by line
	 *
	 * @return void
	 * @author Cédric Levasseur
	 */
	private function linear_grid(){

		// Create line
		$bl =	new BoardLine();
		$notempty = false;

		foreach($this->files as $file){
			// Check rights
			if(!(Judge::view($file))){
				continue;
			}
			// Calculate file ratio
			if(Settings::$thumbs_fixed_width){
				$ratio = 1.5;

			}else{
				$ratio	=	Board::ratio($file);
			}

			// Create new line when sum 
			// of ratios reaches 11
			if($bl->ratio + $ratio > 11){
				$bl->end_line();
				$this->boardlines[] = $bl;
				$bl =	new BoardLine();
				$notempty = false;
			}
			
			// Add item to the line
			$bl->add_item($file,$ratio);
			$notempty = true;
		}
		$bl->end_line();

		if($notempty){
			$this->boardlines[] = $bl;
		}
	}	

}
