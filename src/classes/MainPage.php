<?php
/**
 * This file implements the class MainPage.
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
 * MainPage
 *
 * This is the page containing the Boards.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class MainPage extends Page
{
	
	/// True if the image div should be visible
	private $image_div = false;
	
	/// Boardpanel object
	private $panel;

    /// Specific header content
    private $header_content;
	
	/// Boards class;
	private $panel_class;

	/// Menubar object
	private $menubar;
	
	/// Imagepanel object
	private $image_panel;

	/// Image_panel class
	private $image_panel_class;

	/// Imagepanel object
	private $menu;

	/// Infos
	private $infos;
	
	///Modal
	private $mt;
	private $ma;
	
	///Script
	private $scripts;

	/**
	 * Creates the page
	 *
	 * @author Thibaud Rohmer
	 */
	public function __construct(){	
			
		try{
			$settings=new Settings();
		}catch(FileException $e){
			// If Accounts File missing... Register !
			$this->header();
			new RegisterPage();
			exit;
		}
		
		$this->pageURL = "?f=".urlencode(File::a2r(CurrentUser::$path));
		/// Check how to display current file
		if(is_file(CurrentUser::$path)){
			$this->image_panel			=	new ImagePanel(CurrentUser::$path);
			$this->panel				=	new Board(dirname(CurrentUser::$path));
			$this->header_content       =   $this->image_panel->page_header;
		}else{
			$this->image_panel			=	new ImagePanel();
			$this->panel				=	new Board(CurrentUser::$path);
			$this->header_content       =   $this->panel->page_header;
		}

		/// Create MenuBar
		$this->menubar 		= 	new MenuBar();

		/// Menu
		$this->menu			=	new Menu();
		
		/// Right Menu
		$this->infos 		= 	new Infos();
		
		///Modal
		$this->mt = new ModalTemplate();
		$this->ma = new ModalAdmin();		
		$this->m = new Modal();		
		
		///Scripts
		$this->scripts	= new Scripts();
	}
	
	/**
	 * Display page on the website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		$this->header($this->header_content);
		echo "<body>";
		//Navbar
		$this->menubar->toHTML();
		echo "<div id='content' class='container-fluid'>";
			echo "<div class='row-fluid'>";
				/// Start menu
				echo "<div id='menu' class='span2 well menu'>";
				$this->menu->toHTML();
				echo "</div>\n";
				/// Stop menu		
				echo "<div class='span10 center'>";
					echo "<div class='loading hide'></div>";
					/// ImagePanel
					echo "<div id='image_panel' class='image_panel hide'>\n";
					$this->image_panel->toHTML();
					echo "</div>\n";
					///Linear_panel
					echo "<div id='linear_panel' class='linear_panel hide'><ul class='thumbnails'></ul></div>";						
					///Panel (include boardheader(title+button) , album , images , videos , comments)
					echo "<div class='panel'>\n";
					$this->panel->toHTML();
					echo "</div>\n";					
				echo "</div>\n";	
			echo "</div>\n";					
			$this->mt->toHTML();
			$this->ma->toHTML();		
		echo "</div>";
		$this->scripts->toHTML();		
		echo "</body>\n";		


	}
}

?>
