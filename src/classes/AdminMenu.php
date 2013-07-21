<?php
/**
 * This file implements the class AdminMenu.
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
 * AdminMenu
 *
 * Menu for the admin. Just for the admin. U no admin ? U no menu.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
 class AdminMenu
 {
 	/// Menu options
 	public $options=array(); 	

 	/**
 	 * Build AdminMenu
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function __construct(){
 		$this->options['Abo']	= Settings::_("adminmenu","about");
 		$this->options['Sta']	= Settings::_("adminmenu","stats");
 		$this->options['VTk']	= Settings::_("adminmenu","tokens");
 	 	$this->options['Set']	= Settings::_("adminmenu","settings");
 	 	$this->options['Acc']	= Settings::_("adminmenu","account");
 	 	$this->options['EdA']	= Settings::_("adminmenu","groups");
 	}
 
 	/**
 	 * Display AdminMenu on website
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function toHTML(){
	
		echo "\n\t<ul  class='nav'>\n";
		foreach($this->options as $op=>$val){
		echo "\t\t<li ><a style='text-decoration:none' id='$op' href='?t=Adm&a=$op'>$val</a></li>\n";
 		}
		echo "\t</ul>\n";
 	}

 }
 ?>
