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
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright 2011 Thibaud Rohmer + 2013 Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
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
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright Thibaud Rohmer + Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
<<<<<<< HEAD

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
=======
abstract class Page implements HTMLObject {
    /**
     * Generate an insanely beautiful header.
     * TODO: Title
     *
     * @return void
     * @author Thibaud Rohmer
     */
    public function header($head_content = NULL) {
        #echo "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
        echo "<html xmlns='http://www.w3.org/1999/xhtml'>";
        echo "<head>\n";
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>\n";
        echo "<title>" . Settings::$name . "</title>\n";
        echo "<meta name='author' content='Thibaud Rohmer + Psychedelys'/>\n";
        echo "<link rel='icon' type='image/ico' href='" . Settings::$self_path . "inc/favico.ico'/>";
        /// CSS
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "inc/stylesheets/main.css' type='text/css' media='screen' charset='utf-8'/>\n";
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "inc/stylesheets/page.css' type='text/css' media='screen' charset='utf-8'/>\n";
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "inc/stylesheets/panels.css' type='text/css' media='screen' charset='utf-8'/>\n";
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "inc/stylesheets/forms.css' type='text/css' media='screen' charset='utf-8'/>\n";
        /// Trick to hide "only-script" parts
        //echo "<noscript><style type='text/css'>.noscript_hidden { display: none; }</style></noscript>";
        /// JS
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/jquery.js'/>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/jquery-ui.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/mousewheel.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/jquery.scrollTo.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/jquery.fileupload.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/menu.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/panel.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/slideshow.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/image_panel.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/keyboard.js'></script>\n";
        echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/select.js'></script>\n";
        if (CurrentUser::$admin || CurrentUser::$uploader) {
            echo "<link rel='stylesheet' href='" . Settings::$self_path . "inc/stylesheets/fileupload-ui.css' type='text/css' media='screen' charset='utf-8'>\n";
            echo "<link rel='stylesheet' href='" . Settings::$self_path . "inc/stylesheets/admin.css' type='text/css' media='screen' charset='utf-8'>\n";
            echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/jquery.fileupload-ui.js'></script>\n";
            echo "<script type='text/javascript' src='" . Settings::$self_path . "inc/js/admin.js'></script>\n";
        }
        // Add specific head content if needed
        if ($head_content) {
            echo $head_content;
        }
        echo "</head>";
    }
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4
}
?>
