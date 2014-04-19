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
 class AdminToken  implements HTMLObject
 {

 	/**
 	 * Create about page
 	 * 
 	 * @author Cédric Levasseur
 	 */
 	public function __construct(){
 	}

 	/**
 	 * Display upload page on website
 	 * 
 	 * @author Cédric Levasseur
 	 */
 	public function toHTML(){
		if (!CurrentUser::$admin){
		    // Only admin can see the tokens for now
		    return false;
		}
		
		echo "<div class='row-fluid'>";
		echo "<div class='span12 well'>";
		echo "<legend>".Settings::_("token","tokens")."</legend>\n";
		
		// We still want to display the title so the page is not empty
		if ( !file_exists(CurrentUser::$tokens_file)){
		    return false;
		}
		    echo "<table class='table table-striped well'>";
		    echo "<tbody>";
			foreach(GuestToken::findAll() as $t){
			    echo "<tr>";
			    echo "<td>".$t['path']."<br/><a href='".GuestToken::get_url($t['key'])."' >".$t['key']."</a></td>";
			    echo "<td style='vertical-align:middle;text-align:center;'>";
			    echo "<form id='deltoken-form' class='form-horizontal'  action='WS_Token.delete' method='post' style='margin:0;'>\n";
			    echo "<input type='hidden' name='key' value='".$t['key']."' />";
			    echo "<input type='submit' class='btn btn-primary' value='".Settings::_("token","deletetoken")."' />";
			    echo "</form>";
			    echo "</td>";
			    echo "</tr>";
			}
		echo "</tbody>";
		echo "</table>";
		echo "</div>\n";	
		echo "</div>\n";		
 	}

 }
 ?>