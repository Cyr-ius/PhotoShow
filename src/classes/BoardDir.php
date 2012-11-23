<?php
/**
 * This file implements the class BoardDir.
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
 * BoardDir
 *
 * Implements the displaying of directory on the grid of
 * the Website.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class BoardDir implements HTMLObject
{
	/// URL-encoded relative path to dir
	public $url;
	
	/// Path to dir
	public $path;

	/// Images representing the dir
	public $images;
	
	/**
	 * Construct BoardItem
	 *
	 * @param string $file 
	 * @param string $ratio 
	 * @author Thibaud Rohmer
	 */
	public function __construct($dir,$img=array()){
		$this->path 	= 	$dir;
		$this->url		=	urlencode(File::a2r($dir));
		if(sizeof($img) == 0){
			$this->images 	= array();
		}else{
			$this->images	=	$img;
		}
	}
	
	/**
	 * Display BoardItem on Website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		
		if(sizeof($this->images) > 0){
			$getfile =	"?t=Thb&f=".urlencode(File::a2r($this->images[0]));
			$getafile =	urlencode(File::a2r($this->images[0]));
			
		}else{
			$getfile = 	"./inc/img.png";
			$getafile=	"";
		}	

		echo "\t<li class='dir_img img-rounded ui-draggable ui-droppable directory' style='
				margin-bottom:50px;
				background: 		url(\"$getfile\") no-repeat center center;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: 	cover;				
				'>\n";
		if (File::LastModified($this->path,14)) {echo "<i class='icon-star icon-white' style='position:absolute;'></i>";}
		echo "\t\t<a id='album' class='album' href='?f=".$this->url."'>";
		echo 	"<img src='./inc/img.png' width='100%' height='100%'>";
		echo "</a>\n";	
		echo	"<div class='dirname'>".substr(htmlentities(basename($this->path), ENT_QUOTES ,'UTF-8'),0,26)."</div>";	
		echo 	"\t\t<span class='name hide'>".htmlentities(basename($this->path), ENT_QUOTES ,'UTF-8')."</span>\n";
		echo 	"\t\t<span class='path hide'>".htmlentities(File::a2r($this->path), ENT_QUOTES ,'UTF-8')."</span>\n";
		echo 	"\t\t<span class='img_bg hide'>".$getafile."</span>\n";
		/// Images in the directory
		if( sizeof($this->images) > Settings::$max_img_dir ){
			for($i=0;$i < Settings::$max_img_dir;$i++){
				$pos = floor(sizeof($this->images) *  $i / Settings::$max_img_dir );
				if(Judge::view($this->images[$pos])){
					echo "\t\t<span class='alt_dir_img hide'>".urlencode(File::a2r($this->images[$pos]))."</span>\n";
				}
			}
		}else{
			foreach($this->images as $img){
				if(Judge::view($img)){
					echo 	"\t\t<span class='alt_dir_img hide'>".urlencode(File::a2r($img))."</span>\n";
				}
			}
		}
		echo "\t</li>\n";
	}
}

?>