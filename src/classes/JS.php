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
class JS extends Page
{
	
	private $toPrint;

	private $j;

	public function __construct(){

		/// Execute stuff automagically
		new Admin();

		if(isset($_GET['j'])){
			switch($_GET['j']){

				case "Pag":		/// Pag need load in div #content
								$mb=new MenuBar();
								$m = new Menu();
								$p = new Board();
								$ap = new Infos();
								$mt = new ModalTemplate();
								$ma = new ModalAdmin();
								echo "<div class='row-fluid'>";
									echo "<div id='menu' class='well span2 menu'>";
									$m->toHTML();
									echo "</div>\n";
									echo "<div class='span10 center'>";
										/// ImagePanel
										echo "<div class='image_panel hide'>\n";
										echo "</div>\n";									
										///Linear_panel
										echo "<div id='linear_panel' class='linear_panel hide'><ul class='thumbnails'></ul></div>";						
										///Panel (include boardheader(title+button) , album , images , videos , comments)
										echo "<div class='panel'>\n";
										$p->toHTML();
										echo "</div>\n";					
									echo "</div>\n";
								echo "</div>\n";
								$mt->toHTML();
								$ma->toHTML();
								break;

				case "Pan":		if(is_file(CurrentUser::$path)){
									$b = new ImagePanel(CurrentUser::$path);
									$b->toHTML();
								}else{
									$b = new Board(CurrentUser::$path);
									$b->toHTML();
								}
								break;

				case "Men":		$m = new Menu();
								$m->toHTML();
								break;								
								
				case "MkD":		$f = new AdminPanel(CurrentUser::$path);
								$f->CreateDir_toHTML();
								break;
								
				case "MvD":		$f = new AdminPanel(CurrentUser::$path);
								$f->RenameDir_toHTML();
								break;
								
				case "JSon"	:	$f = new Json();
								$f->toHTML();
								break;								

				default:		break;
			}
		}
	}

	public function toHTML(){

	}
}


?>