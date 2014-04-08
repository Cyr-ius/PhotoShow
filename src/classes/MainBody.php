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

class MainBody extends Page
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
		}catch(Exception $e){
			// If Accounts File missing... Register !
			$this->header();
			new RegisterPage();
			exit;
		}
		
		/// Check how to display current file
		if(is_file(CurrentUser::$path)){
			$this->header_content       =   $this->image_panel->page_header;
		}else{
			$this->header_content       =   $this->panel->page_header;
		}
		
		///MainPage
		$this->mainpage = new MainPage();
		///Message
		$this->message = new Message();
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
	public function toHTML(){
		$this->header($this->header_content);
		echo "<body>";
		//~ echo "<span id='key'>".CurrentUser::$account->key."</span>";
		echo "<div id='mainpage'>";
		$this->mainpage->toHTML();
		echo "</div>";
		$this->message->toHTML();	
		$this->ma->toHTML();			
		$this->mt->toHTML();
		echo "<div id='scripts'>";
		$this->scripts->toHTML();
		echo "</div>";
		echo "</body>\n";		
	}
}

?>
