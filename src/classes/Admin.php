<?php
/**
 * This file implements the class Admin.
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
 * Admin
 *
 * Aministration panel
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
 class Admin extends Page
 {
 	/// Admin page
 	public $page;

 	/// Menu of the Admin page
 	public $menu;

 	/// Admin action
 	static public $action = "Sta";

 	/**
 	 * Create admin page
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function __construct(){
	
		//Section  pour tout le monde
 		if(isset($_GET['a'])){
	 		switch($_GET['a']){
				case "Abo" 	: 	//Display Page About
								$this->page = new AdminAbout();
								break;
	 		}
	 	}
	
 		/// Check that current user is an admin or an uploader
	 	if( !(CurrentUser::$admin || CurrentUser::$uploader) ){
	 		return;
	 	}
 		/// Get actions available for Uploaders too
		///  la variable $this->page correspond à l'affichage de sortie 
		/// puisque on appelle $this->page->toHTML() voir en bas du fichier		
		
		//Section  pour les administrateurs et uploders
 		if(isset($_GET['a'])){
	 		switch($_GET['a']){
				case "Abo" 	: 	//Display Page About
								$this->page = new AdminAbout();
								break;
								
				case "Upl"	:	// POST Upload File
								if(isset($_POST['path'])){
									AdminUpload::upload();
									CurrentUser::$path = File::r2a(stripslashes($_POST['path']));
								}
								$this->page = "Page";
								break;
								//Display Admin Upload managment
		 		case "Aup"	:	$this->page = new AdminUpload();
		 						break;								
	 		}
	 	}

 		/// Check that current user is an admin
	 	if( !(CurrentUser::$admin) ){
	 		return;
	 	}

 		/// Get action
		///  la variable $this->page correspond à l'affichage de sortie 
		/// puisque on appelle $this->page->toHTML() voir en bas du fichier	
		
		//Section uniquement pour les administrateurs
 		if(isset($_GET['a'])){
	 		switch($_GET['a']){
		 		case "Sta"	:	$this->page = new AdminStats();
		 						break;

		 		case "VTk"	:	$this->page = new AdminToken();
		 						break;
									
				case "JS"		:	$this->page = new JS();
								break;

				case "EdA"	:	$this->page = new JSAccounts();
								break;	

		 		case "Acc"	:	$this->page= new AdminAccount();
								break;
									
				case	"ATh"	:	$this->page = new AdminThumbs();
								break;
									
				case "Set"	:	$this->page = new Settings();
								break;									
		 		}				
		}
	}

	 /**
	  * Display admin page
	  * 
	  * @author Thibaud Rohmer
	  */
	public function toHTML(){
		// Create menu
	 	$this->menu = new ModalAdmin();
		//Create content
		$this->page->toHTML();
	}

 }

 ?>
