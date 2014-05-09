<?php
/**
 * This file implements the class JS.
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
 * JS
 *
 * JS Support.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class JS 
{
	public function __construct(){

		if(isset($_GET['j'])){
			switch($_GET['j']){
                                case "ImI"        :     	if(File::Type(CurrentUser::$path)=='Image'){
									$imi = new Image(CurrentUser::$path);
									$imi->toHTML();
								} 
								if(File::Type(CurrentUser::$path)=='Video'){								
									$imi = new Video(CurrentUser::$path);
									$imi->toHTML();
								}
								break;
								
				case "Pan"	:	if(is_file(CurrentUser::$path)){
									$b = new ImagePanel(CurrentUser::$path);
									$b->toHTML();
								}else{
									$b = new Board(CurrentUser::$path);
									$b->toHTML();
								}
								break;

				case "Men"	:	$m = new Menu();
								$m->toHTML();
								break;	

				case "MenBar"	:	$m = new MenuBar();
								$m->toHTML();
								break;	

				case "Item"	:	$item= new BoardItem(CurrentUser::$path);
								$item->toHTML();
								break;	
                                                                
				case "Album"	:	$abm= new BoardDir(CurrentUser::$path);
								$abm->toHTML();
								break;	                                                                
								
				case "LinearP"	:	$lip= new Linear_panel(CurrentUser::$path);
								$lip->toHTML();
								break;
								
				case "Script"	:	$lip= new Scripts(CurrentUser::$path);
								$lip->toHTML();
								break;																

				default:		break;
			}
		}
	}
}


?>
