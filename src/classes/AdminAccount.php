<?php
/**
 * This file implements the class AdminAbout.
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
 * AdminAbout
 *
 * About page
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
 class AdminAccount
 {

 	/**
 	 * Create about page
 	 * 
 	 * @author Cédric Levasseur
 	 */
 	public function __construct($login=null,$key=null){
		$this->AdminLogin = $login;
 	}

 	/**
 	 * Display upload page on website
 	 * 
 	 * @author Cédric Levasseur
 	 */
 	public function toHTML(){
		 echo "<div class='row-fluid'>";
			 echo "<div class='span6 well'>";
				echo "<form id='adminchoiceaccount-form' class='form-horizontal' method='post' action='?t=Adm&a=AcC'>\n";
					echo "<legend>".Settings::_("account","account")."</legend>\n";
					echo "<fieldset>\n";				
					/// Login
					echo "<div class='control-group'>\n";
					echo "<label for='login' class='control-label'>".Settings::_("account","editing")."</label>";
					echo "<div class='controls'>
							<select name='login'>";
							foreach(Account::findall() as $a){
								echo "<option value=\"".addslashes($a['login'])."\"";
								if($this->login == $a['login']){
									echo " selected ";
								}
								echo ">".$a['login']."</option>";
							}
					echo "</select>
						</div>\n";
					echo "</div>\n";
					echo "<div class='controls controls-row'>\n";
					echo "<input class='btn btn-primary' type='submit' value='".Settings::_("login","submit")."'>\n";
					echo "</div>\n";
					echo "</fieldset>\n";		
				echo "</form>\n";			
				$a = new Account($this->AdminLogin);
				$a->toHTML('addUser');
			 echo "</div>\n";
			 echo "<div class='span6 well'>";
				 $p = new RegisterPage();
				 $p->toHTML('addUser');
			 echo "</div>\n";	 
		 echo "</div>\n";	
 	}

 }
 ?>