<?php
/**
 * This file implements the class Menubar.
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
 * Menubar
 *
 * The menubar holds some information, depending on the user.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class MenuBar implements HTMLObject
{
	
	/// True if user is logged in
	private $logged_in	= false;
	
	/// True if user is admin
	private $admin		= false;
	
	/**
	 * Create menubar
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function __construct(){

	}
	
	/**
	 * Display Menubar on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){

		echo "
		<div class='navbar navbar-fixed-top'>
			<div class='navbar-inner'>
				<div class='container-fluid'><!--/.nav-collapse -->
					<a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
					<span class='icon-bar'></span>
					<span class='icon-bar'></span>
					<span class='icon-bar'></span>
					</a>
					<div class='nav-collapse collapse'>
						<ul class='nav pull-left'>
						<li><a id='menu_hide' href='#'><i class='icon-backward'></i></a></li>";
						if(isset(CurrentUser::$account)){
							echo "<li><a href='#' data-href='?t=MyA' data-toggle='modal' data-target='#myModal' data-title='".CurrentUser::$account->login."' data-type='account'><i class='icon-user'></i> ".CurrentUser::$account->login."</a></li>";
							echo "<li><a href='#' id='logout'><i class='icon-off'></i> ".Settings::_("menubar","logout")."</a></li>";
							
							if(CurrentUser::$admin){
							}
						} else {
							echo "<li><a href='#' data-href='?t=Log' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("menubar","login")."' data-type='login'><i class='icon-globe'></i> ".Settings::_("menubar","login")."</a></li>";
							if(!Settings::$noregister){
							echo "<li><a href='#' data-href='?t=Reg' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("menubar","register")."' data-type='register'><i class='icon-pencil'></i> ".Settings::_("menubar","register")."</a></li>";
							}
						}
							echo "<li><a  href='#' data-href='?t=Adm&a=Abo' data-toggle='modal' data-target='#ModalAdmin' ><i class='icon-wrench'></i> ".Settings::_("menubar","admin")."</a></li>";
							echo "<li><a href='.'><i class='icon-home'></i> ".Settings::_("menubar","home")."</a></li>";
						echo "</ul>";
						echo "<ul class='nav pull-right'>";
							echo "<li class='drowpdown'>
									<a id='menu-actions' data-toggle='dropdown' class='dropdown-toggle' href='#'><i class='icon-tasks'></i> Action <b class='caret'></b></a>
									<ul class='dropdown-menu'>";
									if(CurrentUser::$admin){
									echo "<li><a id='button_createdir' href='#' data-href='?f=".urlencode(File::a2r(CurrentUser::$path))."&t=MkD' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("adminpanel","create")."'><i class='icon-folder-open'></i> ".Settings::_("adminpanel","create")."</a></li>\n";				
									echo "<li><a id='button_renamedir' href='#' data-href='?f=".urlencode(File::a2r(CurrentUser::$path))."&t=MvD' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("adminpanel","rename")."'><i class='icon-pencil'></i> ".Settings::_("adminpanel","rename")."</a></li>\n";
									echo "<li><a id='button_rights' href='#' data-href='?f=".urlencode(File::a2r(CurrentUser::$path))."&t=Rights' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("menubar","rightsset")."'><i class='icon-screenshot'></i> ".Settings::_("menubar","rightsset")."</a></li>\n";
									echo "<li><a id='button_token' href='#' data-href='?f=".urlencode(File::a2r(CurrentUser::$path))."&j=Tokens' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("token","tokens")."'><i class='icon-share'></i> ".Settings::_("token","tokens")."</a></li>\n";
									echo "<li><a id='button_thb' href='#' ><i class='icon-picture'></i> ".Settings::_("adminpanel","createthumbnails")."</a></li>\n";
									echo "<li><a id='edit_textinfo' href='#'><i class='icon-edit'></i> ".Settings::_("textinfo","edit")."</a></li>\n";
									}
									if(!Settings::$nodownload){
									echo "<li><a id='button_download' href='?f=".urlencode(File::a2r(CurrentUser::$path))."&t=Zip'><i class='icon-download'></i> ".Settings::_("boardheader","download")."</a></li>";
									}	
									if(!Settings::$nocomments){
									echo "<li><a id='button_comm' href='#' data-href='?f=".urlencode(File::a2r(CurrentUser::$path))."&t=Com' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("comments","comments")."'data-type='comments'><i class='icon-comment'></i> ".Settings::_("comments","comments")."</a></li>\n";						
									}	
									echo "<li><a id='button_exif' href='#'><i class='icon-info-sign'></i> Exif</a></li>\n";						
								echo "</ul>";
							echo "</li>";
						echo "</ul>";
						echo "<ul class='nav pull-right'>";
							if(CurrentUser::$admin || CurrentUser::$uploader){
							echo "<li class='hide uploadbtn'><a id='button_upload' href='#' ><i class='icon-upload'></i> ".Settings::_("adminpanel","upload")."</a></li>\n";	
							echo "<li><a id='bin' class='btn btn-small  bin' style='padding-top:5px;padding-bottom:5px;margin-right:5px;'><span class='path hide'>bin</span><i class=' icon-trash'></i></a></li>\n";							
							}
						echo "</ul>";
						echo"
				        </div><!--/.nav-collapse -->
				</div>
			</div>
		</div>";
		}
		//echo "<a href='?a=rss'>RSS <img src='inc/rss.png' height='11px'></a>\n";
}
?>
