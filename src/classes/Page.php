<?php
/**
 * This file implements the class Page.
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
 * Page
 *
 * The page holds all of the data. This class build the entire
 * structure of the website, as it is viewed by the user.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

abstract class Page implements HTMLObject
{
		/**
		 * Generate an insanely beautiful header.
		 * TODO: Title
		 *
		 * @return void
		 * @author Thibaud Rohmer
		 */
		public function header($head_content=NULL){
			echo "<!DOCTYPE html>\n";
			echo "<html lang='fr'>";
			echo "<head>\n";
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n";
			echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
			echo "<title>".Settings::$name."</title>\n";
			echo "<link rel='icon' type='image/ico' href='".Settings::$icon_path."'>";

			/// CSS
			echo "<link rel='stylesheet' href='src/stylesheets/normalize.css' type='text/css' media='screen' charset='utf-8'>";
			echo "<link rel='stylesheet' href='inc/bootstrap/css/bootstrap.min.css'>";
			echo "<link rel='stylesheet' href='inc/bootstrap/css/bootstrap-modal.css'>";			
			echo "<link rel='stylesheet' href='http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css'>";		
			echo "<link rel='stylesheet' href='inc/mCustomScrollbar/jquery.mCustomScrollbar.css'>";	
			echo "<link rel='stylesheet' href='inc/colorpicker/css/colorpicker.css'>";	
			echo "<link rel='stylesheet' href='inc/assets/css/video-default.css'>";	
			echo "<link rel='stylesheet' href='inc/messenger/css/messenger.css'>";	
			echo "<link rel='stylesheet' href='inc/messenger/css/messenger-theme-future.css'>";	
			echo "<link rel='stylesheet' href='src/stylesheets/jquery.plupload.queue.css'>";
			echo "<link rel='stylesheet' href='src/stylesheets/perso.css'>";
			echo "<link rel='stylesheet' href='inc/bootstrap/css/bootstrap-responsive.min.css'>";
			
			/// Trick to hide "only-script" parts
	 		echo "<noscript><style>.noscript_hidden { display: none; }</style></noscript>";

            // Add specific head content if needed
            if ($head_content)
            {
                echo $head_content;
            }
			echo "</head>";
		}
}
?>
