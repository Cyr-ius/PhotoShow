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
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright 2011 Thibaud Rohmer + 2013 Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
/**
 * Admin
 *
 * Aministration panel
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright Thibaud Rohmer + Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
<<<<<<< HEAD
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
		
		//Section uniquement pour les administrateurs et uploders
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
									$this->page = '';
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
		
		//Section uniquement pour les administrateurs
 		if(isset($_GET['a'])){
	 		switch($_GET['a']){
		 		case "Sta"		:	$this->page = new AdminStats();
		 							break;

		 		case "VTk"		:	$this->page = new AdminToken();
		 							break;
									
				case "JS"			:	$this->page = new JS();
									break;

				case "EdA"		:	$this->page = new JSAccounts();
									break;	

				case "CTk"		:	//Create Token
									if (isset($_POST['token'])) {
									GuestToken::create(CurrentUser::$path);
									}
									$this->page = new AdminToken();
									break;

				case "DTk" 		:	//Delete Token
									if(isset($_POST['tokenkey'])){
										GuestToken::delete($_POST['tokenkey']);
									}
									$this->page = new AdminToken();
									break;	

				case "AcC"		:	//Search Account
									if(isset($_POST['login'])){
										$this->page= new AdminAccount($_POST['login']);
									}
									break;

		 		case "Acc"		:	//Edit Account
									if(isset($_POST['edit'])){
										Account::edit($_POST['login'],NULL,$_POST['password'],$_POST['name'],$_POST['email'],NULL,$_POST['language']);
										$this->page= new AdminAccount($_POST['login']);
									} else {
										$this->page= new AdminAccount();
									}
									break;

				case "GC"		:	//Create Group
									if(isset($_POST['group'])){
										Group::create($_POST['group']);
									}
									$this->page = new JSAccounts();
									break;
									
				case "GDe"		:	//Delete Group
									if(isset($_POST['name'])){
										Group::delete($_POST['name']);
									}
									$this->page = new JSAccounts();
									break;									

				case "AAc"		:	//Create Account
									if($_POST){
										Account::create($_POST['login'],$_POST['password'],$_POST['verif']);
										unset($_POST);
									}
									$this->page= new AdminAccount($_POST['login']);
									break;
									
				case "ADe"		:	//Delete Account
									if(isset($_POST['name'])){
										Account::delete($_POST['name']);
									}
									$this->page = new JSAccounts();
									break;									
				
				case "AGA"		:	// Add user in group
									$a = new Account($_POST['acc']);
									$a->add_group($_POST['group']);
									$a->save();
									$this->page = new JSAccounts();
									break;

				case "AGR"		:	//Remove user of group
									if($_POST){
										$a = new Account($_POST['acc']);
										$a->remove_group($_POST['group']);
										$a->save();
										unset($_POST);
									}
									$this->page = new JSAccounts();
									break;

				case "CDe"		:	//Delete Comment
									if(isset($_POST['date'])){
										Comments::delete(File::r2a($_POST['path']),$_POST['date']);
									}
									$this->page = new Comments(CurrentUser::$path);
									break;
				
				case "GAl"		:	//Generate Thumbs
									if(isset($_POST['path'])){
										Settings::gener_all(File::r2a(stripslashes($_POST['path'])));
									}
									$this->page = new Settings();
									break;
									
				case "DAl"		:	//Delete Thumbs
									if(isset($_POST['cleanpath'])){
										Settings::cleanthumbs(File::r2a(stripslashes($_POST['cleanpath'])));
									}
									$this->page = new Settings();
									break;
									
				case "Set" 		:	//Save Preferences settings
									if(isset($_POST['name'])){
										Settings::set();
									}
									$this->page = new Settings();
									break;
									
				case "Tis"	:		//Save Information
									if(isset($_POST['f'])){
										TextInfo::Save_File($_POST['f'],$_POST['title'],$_POST['author'],$_POST['contain']);
									}
									$this->page = new TextInfo(CurrentUser::$path);
									break;	
									
				case "Tid"	:		//Delete Information
									if(isset($_POST['f'])){
										TextInfo::Delete_File($_POST['f']);
									}
									$this->page = new TextInfo(CurrentUser::$path);
									break;
									
				case "RTy"	:		//Change permission Public/Private
									if(isset($_POST['type'])){
										if ($_POST['type']=='Pri'){
											Judge::edit(CurrentUser::$path,array(),array(),true);
										}
										if ($_POST['type']=='Pub'){
											Judge::edit(CurrentUser::$path);
										}
									}
									$this->page = new Judge (CurrentUser::$path);
									break;
								

				case "Rig"	:		//Edit permissions
									if (isset($_POST['users'])|| isset($_POST['groups'])) {
										Judge::edit(CurrentUser::$path,$_POST['users'],$_POST['groups'],true);
									}
									$this->page = new Judge (CurrentUser::$path);
									break;
		 		}
				
				
		}

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
=======
class Admin extends Page {
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
    public function __construct() {
        /// Check that current user is an admin or an uploader
        if (!(CurrentUser::$admin || CurrentUser::$uploader)) {
            return;
        }
        /// Get actions available for Uploaders too
        if (isset($_GET['a'])) {
            switch ($_GET['a']) {
                case "Abo":
                    $this->page = new AdminAbout();
                break;
                case "Upl":
                    if (isset($_POST['path'])) {
                        AdminUpload::upload();
                        CurrentUser::$path = File::r2a(stripslashes($_POST['path']));
                    }
                    $this->page = new AdminFiles();
                break;
                case "Mov":
                    if (isset($_POST['pathFrom'])) {
                        try {
                            CurrentUser::$path = File::r2a(dirname(stripslashes($_POST['pathFrom'])));
                        }
                        catch(Exception $e) {
                            CurrentUser::$path = Settings::$photos_dir;
                        }
                    }
                    AdminMove::move();
                    if (isset($_POST['move']) && $_POST['move'] == "rename") {
                        try {
                            if (is_dir(File::r2a(stripslashes($_POST['pathFrom'])))) {
                                CurrentUser::$path = dirname(File::r2a(stripslashes($_POST['pathFrom']))) . "/" . stripslashes($_POST['pathTo']);
                            }
                        }
                        catch(Exception $e) {
                            CurrentUser::$path = Settings::$photos_dir;
                        }
                    }
                    $this->page = new AdminFiles();
                break;
                case "Del":
                    if (isset($_POST['del'])) {
                        if (!is_array($_POST['del'])) {
                            CurrentUser::$path = dirname(File::r2a(stripslashes($_POST['del'])));
                        } else {
                            CurrentUser::$path = dirname(File::r2a(stripslashes($_POST['del'][0])));
                        }
                        AdminDelete::delete();
                    }
                    $this->page = new AdminFiles();
                break;
            }
        }
        /// Check that current user is an admin
        if (!(CurrentUser::$admin)) {
            return;
        }
        /// Get action
        if (isset($_GET['a'])) {
            switch ($_GET['a']) {
                case "Sta":
                    $this->page = new AdminStats();
                break;
                case "VTk":
                    $this->page = new GuestToken();
                break;
                case "DTk":
                    if (isset($_POST['tokenkey'])) {
                        GuestToken::delete($_POST['tokenkey']);
                    }
                    $this->page = new GuestToken();
                break;
                case "Acc":
                    if (isset($_POST['edit'])) {
                        Account::edit($_POST['login'], $_POST['old_password'], $_POST['password'], $_POST['name'], $_POST['email'], NULL, $_POST['language']);
                    }
                    if (isset($_POST['login'])) {
                        $this->page = new Account($_POST['login']);
                    } else {
                        $this->page = CurrentUser::$account;
                    }
                break;
                case "GC":
                    Group::create($_POST['group']);
                    $this->page = new JSAccounts();
                break;
                case "AAc":
                    Account::create($_POST['login'], $_POST['password'], $_POST['verif']);
                    $this->page = new JSAccounts();
                break;
                case "AGA":
                    $a = new Account($_POST['acc']);
                    $a->add_group($_POST['group']);
                    $a->save();
                    $this->page = CurrentUser::$account;
                break;
                case "AGR":
                    $a = new Account($_POST['acc']);
                    $a->remove_group($_POST['group']);
                    $a->save();
                    $this->page = CurrentUser::$account;
                break;
                case "ADe":
                    Account::delete($_POST['name']);
                    $this->page = new JSAccounts();
                break;
                case "GDe":
                    Group::delete($_POST['name']);
                    $this->page = new JSAccounts();
                break;
                case "CDe":
                    CurrentUser::$path = File::r2a($_POST['image']);
                    Comments::delete($_POST['image'], $_POST['date']);
                    $this->page = new MainPage();
                break;
                case "Fil":
                    $this->page = new AdminFiles();
                break;
                case "JS":
                break;
                case "EdA":
                    $this->page = new JSAccounts();
                break;
                case "GAl":
                    if (isset($_POST['path'])) {
                        Settings::gener_all(File::r2a(stripslashes($_POST['path'])));
                    }
                case "Set":
                    if (isset($_POST['name'])) {
                        Settings::set();
                    }
                    $this->page = new Settings();
                break;
            }
        }
        if (!isset($this->page)) {
            $this->page = new AdminStats();
        }
        /// Create menu
        $this->menu = new AdminMenu();
    }
    /**
     * Display admin page
     *
     * @author Thibaud Rohmer
     */
    public function toHTML() {
        $this->header();
        echo "<div class='menu'>\n";
        $this->menu->toHTML();
        echo "</div>\n";
        echo "<div class='center'>\n";
        if (isset($_GET['a']) && $_GET['a'] == "JS") {
            $this->page = new JS();
        } else {
            $this->page->toHTML();
        }
        echo "</div>";
    }
}
?>
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4
