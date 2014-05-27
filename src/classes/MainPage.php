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
	
	//Message Object
	//~ private $message;
	
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
		}catch(Exception $e){
			// If Accounts File missing... Register !
			$this->header();
			new RegisterPage();
			exit;
		}

		/// Check how to display current file
		if(is_file(CurrentUser::$path)){
			$this->bigpanel_visible			=	"";			
			$this->image_panel				=	new ImagePanel(CurrentUser::$path);
			$this->panel					=	new Board(dirname(CurrentUser::$path));
			$this->linear_panel				=	new Linear_panel(CurrentUser::$path);
			$this->panel_visible				=	"hide";
			$this->header_content      		=   	$this->image_panel->page_header;

		}else{
			$this->bigpanel_visible			=	"hide";						
			$this->image_panel				=	new ImagePanel();		
			$this->panel					=	new Board(CurrentUser::$path);
			$this->linear_panel				=	new Linear_panel(CurrentUser::$path);	
			$this->panel_visible				=	"";	
			$this->header_content       		=  	$this->panel->page_header;
		}

		/// Create MenuBar
		$this->menubar 		= 	new MenuBar();

		/// Menu
		$this->menu			=	new Menu();
		
		///Message
		//~ $this->message = new Message();
		
		///Modal
		$this->mt = new ModalTemplate();
		$this->ma = new ModalAdmin();			
		///Scripts
		$this->scripts	= new Scripts();

	}
	
	/**
	 * Display page on the website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML($main_only=null){
	
		if(!$main_only)  {
		$this->header($this->header_content);
		echo "<body>";
		}
		echo "<div id='mainpage'>";// -- Start Main
			//Navbar
			echo "<div id='menubar'>\n";
				$this->menubar->toHTML();
			echo "</div>\n";

			echo "<div id='content'>\n"; // -- Start Content
					/// Start menu
					echo "<div id='menu' class='well menu'>\n";
					$this->menu->toHTML();
					echo "</div>\n";
					/// Stop menu		
					echo "<div class='loading hide'></div>";
					echo "<div class='bigpanel $this->bigpanel_visible'>\n";
						echo "<div class='content_panel'>";
							echo "<div class='image_panel'>\n";
							$this->image_panel->toHTML();
							echo "</div>\n";
							echo "<div class='well exif '></div>";
							$this->linear_panel->toHTML();
						echo "</div>\n";
					echo "</div>\n";
					///Panel (include boardheader(title+button) , album , images , videos , comments)
					echo "<div class='panel $this->panel_visible'>\n";
						echo "<div class='content_panel'>";
							if (Index::$welcome) {
								echo "<h2>Welcome to Photoshow </h2>\n";
								echo "<div id='welcome' class='well'>\n";
								echo " Please, clic on the link below <br/><br/><a href='#' data-href='?t=Reg' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("menubar","register")."' data-type='register'><i class='icon-pencil'></i> ".Settings::_("menubar","register")." the first account Administrator</a>\n";
								echo "</div>\n";		
							} else {
								$this->panel->toHTML();
							}
						echo "</div>\n";	
					echo "</div>\n";	
			echo "</div>\n"; // -- End Content
			$this->ma->toHTML();			
			$this->mt->toHTML();
		echo "</div>\n"; // -- End Main
		if(!$main_only)  {	
		echo "</body>\n";		
		$this->scripts->toHTML();
		}
	}
}

?>
