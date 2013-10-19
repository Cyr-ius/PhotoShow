<?php
/**
 * This file implements the class ImageBar.
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
 * ImageBar
 *
 * The ImageBar contains some buttons insanely awesome
 * buttons, incredibly usefull. Yeah, it rocks.
 * 
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class ImageBar
{

	/// Buttons to display
	private $buttons = array();


	/**
	 * Create the ImageBar
	 * 
	 * @author Thibaud Rohmer
	 */
	public function __construct($fs=false){
		
		$this->buttons['prev'] = array('icon'=>"icon-step-backward","url"=>"?p=p");
		$this->buttons['linear'] = array('icon'=>"  icon-camera","url"=> "");
		$this->buttons['back'] = array('icon'=>" icon-th","url"=>"");
		if(!Settings::$nodownload){
			$this->buttons['img'] = array('icon'=>"icon-picture","url"=>"");
			$this->buttons['get'] = array('icon'=>"icon-download-alt","url"=>"");
		}
		$this->buttons['slideshow'] = array('icon'=>" icon-play","url"=> "");
		$this->buttons['next'] = array('icon'=>"icon-step-forward","url"=>"?p=n");
		
	}

	/**
	 * Display ImageBar on Website
	 * 
	 * @author Thibaud Rohmer
	 */
	 public function toHTML(){
		echo "<div class='image_bar'>\n";
		echo "<ul>\n";
	 	foreach($this->buttons as $key=>$value){
	 		echo "<li id='$key'><a href='".$value['url']."' alt='$key'><i  class='".$value['icon']." icon-white'></i></a></li>";
	 	}
		echo "</ul>\n";
		echo "</div>\n";		
	 }

}

?>