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
 class AdminAbout  implements HTMLObject
 {
 	public function __construct(){

 	}
        
 	public function toHTML(){
		echo "<div class='row-fluid'>";
 		echo "<h3>PhotoShow</h3>";
 		echo "<ul class='breadcrumb'>";
 		echo "<li><a href='https://github.com/cyr-ius/PhotoShow'>PhotoShow on GitHub</a></li>\n";
 		echo "<li><a href='https://github.com/cyr-ius/PhotoShow/wiki/Tips'>Tips !</a></li>\n";
 		echo "</ul>\n";

 		echo "<h3>Me</h3>";
 		echo "<div style='text-align:center;'>";
		echo "<p><img src='inc/me.png' align='center' style='border-radius: 5px; background-color: white; height: 120px; width: auto;border-radius:5px; -moz-border-radius:5px;'></p>";
 		echo "<ul class='breadcrumb'>";
 		echo "<li><a href='mailto:cedric.levasseur@ipocus.net'>Email</a></li>\n";
 		echo "<li><a href='https://github.com/Cyr-ius/PhotoShow'>GitHub</a></li>\n";
 		//~ echo "<li><a href='https://plus.google.com/114933352963292387937/about'>Google Profile</a></li>\n";
                
               echo " <div id='fb-root'></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = '//connect.facebook.net/fr_FR/all.js#xfbml=1';
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>";
                echo "<li><div class='fb-like' data-href='https://www.facebook.com/pages/Photoshow-Evolution' data-layout='standard' data-action='like' data-show-faces='true' data-share='true'></div></li>";
 		echo "</ul>\n";
		echo "</div>\n";		
 		echo "<h3>If you like PhotoShow ... </h3>";
 		echo "<ul class='breadcrumb'>";
 		echo "<li>Spread the word ! Tell it to your friends :)</li>\n";
 		echo "<li>Tweet/Post/Blog/Whatever about it (#photoshow)</li>\n";
 		echo "<li></li>";
		echo "</ul>\n";		
		//~ echo "<ul style='text-align:center;'>";
 		//~ echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		//~ <input type="hidden" name="cmd" value="_s-xclick">
		//~ <input type="hidden" name="hosted_button_id" value="EJCH63L4226YN">
		//~ <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		//~ <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		//~ </form>';
		//~ echo "</ul>\n";
		echo "</div>\n";

 	}

 }
 ?>