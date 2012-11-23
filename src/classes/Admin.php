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
 	static public $action = "stats";

 	/**
 	 * Create admin page
 	 * 
 	 * @author Thibaud Rohmer
 	 */
 	public function __construct(){

 		/// Check that current user is an admin or an uploader
	 	if( !(CurrentUser::$admin || CurrentUser::$uploader) ){
	 		return;
	 	}
 		/// Get actions available for Uploaders too
		///  la variable $this->page correspond à l'affichage de sortie 
		/// puisque on appelle $this->page->toHTML() voir en bas du fichier		
 		if(isset($_GET['a'])){
	 		switch($_GET['a']){
	 			case "Abo" 		: 	$this->page = new AdminAbout();
	 								break;
	 								
		 		case "Upl"		:	if(isset($_POST['path'])){
		 								AdminUpload::upload();
		 								CurrentUser::$path = File::r2a(stripslashes($_POST['path']));
		 							}
									$this->page = "Page";
		 							break;
				
				case "Mov"		:	if(isset($_POST['pathFrom'])){
										try{
	 										CurrentUser::$path = File::r2a(dirname(stripslashes($_POST['pathFrom'])));	
										}catch(Exception $e){
											CurrentUser::$path = Settings::$photos_dir;
										}
									}
									$json=AdminMove::move();
	 								
	 								if(isset($_POST['move']) && $_POST['move']=="rename"){
										try{
								//			if(is_dir(File::r2a(stripslashes($_POST['pathFrom'])))){
	 							//				CurrentUser::$path = dirname(File::r2a(stripslashes($_POST['pathFrom'])))."/".stripslashes($_POST['pathTo']);	
	 							//			}
										}catch(Exception $e){
											CurrentUser::$path = Settings::$photos_dir;
										}
									}
		 							
									$this->page = JS::toHTML($json);
									break;

				case "Del"		:	if(isset($_POST['del'])){
		 								CurrentUser::$path = dirname(File::r2a(stripslashes($_POST['del'])));
		 								$json = AdminDelete::delete();
		 							}
									$this->page = JS::toHTML($json);
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
 		if(isset($_GET['a'])){
	 		switch($_GET['a']){
		 		case "Sta"		:	$this->page = new AdminStats();
		 							break;

		 		case "VTk"		:	$this->page = new AdminToken();
		 							break;

				case "DTk" 		:	if(isset($_POST['tokenkey'])){
										GuestToken::delete($_POST['tokenkey']);
									}
									$this->page = new AdminToken();
									break;	

				case "AcC"		:	if(isset($_POST['login'])){
										$this->page= new AdminAccount($_POST['login']);
									}
									break;

		 		case "Acc"		:	if(isset($_POST['edit'])){
										Account::edit($_POST['login'],NULL,$_POST['password'],$_POST['name'],$_POST['email'],NULL,$_POST['language']);
										$this->page= new AdminAccount($_POST['login']);
									} else {
										$this->page= new AdminAccount();
									}
									break;

				case "GC"		:	if(isset($_POST['group'])){
										Group::create($_POST['group']);
									}
									$this->page = new JSAccounts();
									break;
									
				case "GDe"		:	if(isset($_POST['name'])){
										Group::delete($_POST['name']);
									}
									$this->page = new JSAccounts();
									break;									

				case "AAc"		:	if($_POST){
										Account::create($_POST['login'],$_POST['password'],$_POST['verif']);
										unset($_POST);
									}
									$this->page= new AdminAccount($_POST['login']);
									break;
									
				case "ADe"		:	if(isset($_POST['name'])){
										Account::delete($_POST['name']);
									}
									$this->page = new JSAccounts();
									break;									
				
				case "AGA"		:	$a = new Account($_POST['acc']);
									$a->add_group($_POST['group']);
									$a->save();
									$this->page = new JSAccounts();
									break;

				case "AGR"		:	if($_POST){
										$a = new Account($_POST['acc']);
										$a->remove_group($_POST['group']);
										$a->save();
										unset($_POST);
									}
									$this->page = new JSAccounts();
									break;

				case "CDe"		:	if(isset($_POST['date'])){
										Comments::delete(File::r2a($_POST['path']),$_POST['date']);
									}
									$this->page = new Comments(CurrentUser::$path);
									break;

				//~ case "Fil"			:	$this->page = new AdminFiles();
									//~ break;

				case "JS"			:	$this->page = new JS();
									break;

				case "EdA"		:	$this->page = new JSAccounts();
									break;
				
				case "GAl"		:	if(isset($_POST['path'])){
										Settings::gener_all(File::r2a(stripslashes($_POST['path'])));
									}
									$this->page = new Settings();
									break;
									
				case "DAl"		:	if(isset($_POST['cleanpath'])){
										Settings::cleanthumbs(File::r2a(stripslashes($_POST['cleanpath'])));
									}
									$this->page = new Settings();
									break;
									
				case "Set" 		:	if(isset($_POST['name'])){
										Settings::set();
									}
									$this->page = new Settings();
									break;
									
				case "Tis"	:		if(isset($_POST['f'])){
										TextInfo::Save_File($_POST['f'],$_POST['title'],$_POST['author'],$_POST['contain']);
									}
									$this->page = new TextInfo(CurrentUser::$path);
									break;	
									
				case "Tid"	:		if(isset($_POST['f'])){
										TextInfo::Delete_File($_POST['f']);
									}
									$this->page = new TextInfo(CurrentUser::$path);
									break;
									
				case "RTy"	:		if(isset($_POST['type'])){
										if ($_POST['type']=='Pri'){
											Judge::edit(CurrentUser::$path,array(),array(),true);
										}
										if ($_POST['type']=='Pub'){
											Judge::edit(CurrentUser::$path);
										}
									}
									$this->page = new Judge (CurrentUser::$path);
									break;
								

				case "Rig"	:		if (isset($_POST['users'])|| isset($_POST['groups'])) {
										Judge::edit(CurrentUser::$path,$_POST['users'],$_POST['groups'],true);
									}
									$this->page = new Judge (CurrentUser::$path);
									break;
		 		}
				
				
		}
		//~ if(!isset($this->page)){
			//~ $this->page = new Settings();			
		//~ }

	 	/// Create menu
	 	$this->menu = new AdminMenu();

	}

	 /**
	  * Display admin page
	  * 
	  * @author Thibaud Rohmer
	  */
	public function toHTML(){
		if($_GET['a']=="JS"){
			$this->page = new JS();
		}else{
		 	$this->page->toHTML();			
		}
	}

 }

 ?>
