<?php
/**
 * This file implements the class LoginPage.
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
 * LoginPage
 *
 * Lets a user log in.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class LoginPage extends Page
{
	
	/**
	 * Create Login Page
	 *
	 * @author Thibaud Rohmer
	 */
	public function __construct(){
			
	}
	
	/**
	 * Display Login Page on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){

		echo "<form id='logins-form' class='form-horizontal'  method='post' action='WS_Account.login'>\n";
		echo "<fieldset>\n";		
		/// Login
		echo "<div class='control-group'>\n";
		echo "<label for='login' class='control-label'>".Settings::_("login","login")."</label>";
		echo "<div class='controls'><input id='login' class='input-large' type='text' name='login' value='' placeholder='".Settings::_("login","login")."'></div>\n";
		echo "</div>\n";
		/// Password
		echo "<div class='control-group'>\n";
                 echo "<label for='password' class='control-label'>".Settings::_("login","pass")."</label>";
		echo "<div class='controls'><input id='password' class='input-large' type='password' name='password' value='' placeholder='".Settings::_("login","pass")."'></div>\n";
		echo "</div>\n";		
		echo "<div class='controls controls-row'>\n";
		echo "<input class='btn btn-primary' type='submit' value='".Settings::_("login","submit")."'>\n";
		echo "</div>\n";
		echo "</fieldset>\n";	
		echo "</form>\n";	
    }    
}
?>
