<?php
/**
 * This file implements the class RegisterPage.
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
 * RegisterPage
 *
 * This is the page that lets the user create an account.
 * If there is no account created yet, the acount created
 * here will be the admin.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */


class RegisterPage extends Page
{
	
	private $admin_account;

	private $included;

	/**
	 * Create Register Page
	 *
	 * @author Thibaud Rohmer
	 */
	public function __construct($admin_account = false, $included = false){
		$this->admin_account = $admin_account;		
		$this->included 	 = $included;
	}
	
	/**
	 * Display Register Page on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
    public function toHTML($addUser=null){

		if ($addUser) {
			echo "<form id='adminregister-form' class='form-horizontal adduser' method='post' action='?t=Adm&a=AAc'>\n";
		}else{
			echo "<form id='register-form' class='form-horizontal' method='post' action='?t=Reg'>\n";
		}
		echo "<fieldset>\n";
		echo "<legend>".Settings::_("account","createaccount")."</legend>\n";
            /// Login
		echo "<div class='control-group'>\n";
		echo "<label for='login' class='control-label'>".Settings::_("register","logintxt")."</label>";
		echo "<div class='controls'><input id='login' class='span12' type='text' name='login' value=''></div>\n";
		echo "</div>\n";

            /// Password
		echo "<div class='control-group'>\n";
                 echo "<label for='password' class='control-label'>".Settings::_("register","passtxt")."</label>";
		echo "<div class='controls'><input id='password' class='span12' type='password' name='password' value=''></div>\n";
		echo "</div>\n";
            /// Verif
		echo "<div class='control-group'>\n";
		echo "<label for='verif' class='control-label'>".Settings::_("register","veriftxt")."</label>";
		echo "<div class='controls'><input id='verif' class='span12' type='password' name='verif' value=''></div>\n";
		echo "</div>\n";
		echo "<div class='controls controls-row'>\n";
		echo "<input class='btn btn-primary' type='submit' value='".Settings::_("register","submit")."'>\n";
		echo "</div>\n";
		echo "</fieldset>\n";
		echo "</form>\n";	
    }
}
?>
