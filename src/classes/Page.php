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
abstract class Page implements HTMLObject {
    /**
     * Generate an insanely beautiful header.
     * TODO: Title
     *
     * @return void
     * @author Thibaud Rohmer
     */
    public function header($head_content = NULL) {
        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
        echo "<html>";
        echo "<head>\n";
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n";
        echo "<title>" . Settings::$name . "</title>\n";
        echo "<meta name='author' content='Thibaud Rohmer'>\n";
        echo "<link rel='icon' type='image/ico' href='" . Settings::$self_path . "/inc/favico.ico'>";
        /// CSS
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "/inc/stylesheets/main.css' type='text/css' media='screen' charset='utf-8'>\n";
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "/inc/stylesheets/page.css' type='text/css' media='screen' charset='utf-8'>\n";
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "/inc/stylesheets/panels.css' type='text/css' media='screen' charset='utf-8'>\n";
        echo "<link rel='stylesheet' href='" . Settings::$self_path . "/inc/stylesheets/forms.css' type='text/css' media='screen' charset='utf-8'>\n";
        /// Trick to hide "only-script" parts
        echo "<noscript><style>.noscript_hidden { display: none; }</style></noscript>";
        /// JS
        echo "<script src='" . Settings::$self_path . "/inc/js/jquery.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/jquery-ui.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/mousewheel.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/jquery.scrollTo.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/jquery.fileupload.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/menu.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/panel.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/slideshow.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/image_panel.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/keyboard.js'></script>\n";
        echo "<script src='" . Settings::$self_path . "/inc/js/select.js'></script>\n";
        if (CurrentUser::$admin || CurrentUser::$uploader) {
            echo "<link rel='stylesheet' href='" . Settings::$self_path . "/inc/stylesheets/fileupload-ui.css' type='text/css' media='screen' charset='utf-8'>\n";
            echo "<link rel='stylesheet' href='" . Settings::$self_path . "/inc/stylesheets/admin.css' type='text/css' media='screen' charset='utf-8'>\n";
            echo "<script src='" . Settings::$self_path . "/inc/js/jquery.fileupload-ui.js'></script>\n";
            echo "<script src='" . Settings::$self_path . "/inc/js/admin.js'></script>\n";
        }
        // Add specific head content if needed
        if ($head_content) {
            echo $head_content;
        }
        echo "</head>";
    }
}
?>
