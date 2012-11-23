<?php
/**
 * This file implements the class BoardItem.
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
 * BoardItem
 *
 * Implements the displaying of an item of the grid on
 * the Website.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class BoardItem implements HTMLObject
{
	/// URL-encoded relative path to file
	public $file;
	
	/// Path to file
	public $path;

	/// Ratio of the file
	public $ratio;
	
	/// Item width
	public $width;
	
	/**
	 * Construct BoardItem
	 *
	 * @param string $file 
	 * @param string $ratio 
	 * @author Thibaud Rohmer
	 */
	public function __construct($file,$ratio){
		$this->path 	= 	$file;
		$this->file		=	urlencode(File::a2r($file));
		$this->ratio	=	$ratio;
	}
	
	/**
	 * Display BoardItem on Website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		
		/// If item is small, display its thumb. Else, display the item
		$getfile =	$this->width>25 
					? "t=Img&f=$this->file" 
					: "t=Thb&f=$this->file";
				
		/// We display the image as a background
		echo "\t<li class='item' ";
			if(CurrentUser::$path == $this->path){
			echo " selected ";
			}
		echo ">\n";
		echo "\t\t<a class='thumbnail' href='?f=".$this->file."'>";
		//~ echo "<img class='loadimg' src='./inc/spacer.gif' style='background: url(\"?".$getfile."\") no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;'>";
		echo "<img class='lazy' src='./inc/spacer.gif' data-original='?".$getfile."' width='100%' height='100%'>";
		echo "</a>\n";
		echo 	"\t\t<span class='name hide'>".htmlentities(basename($this->path), ENT_QUOTES ,'UTF-8')."</span>\n";
		echo 	"\t\t<span class='path hide'>".htmlentities(File::a2r($this->path), ENT_QUOTES ,'UTF-8')."</span>\n";
		echo 	"\t\t<span class='pathd hide'>".$getfile."</span>\n";
		echo "\t</li>\n";

	}
	
	/**
	 * Calculate width (in percent) of the item : 
	 * 90 * item_ratio / line_ratio
	 *
	 * @param string $r 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function set_width($line_ratio){
		$this->width = 90 * $this->ratio / $line_ratio;
	}
}

?>