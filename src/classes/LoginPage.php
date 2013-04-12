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
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright 2011 Thibaud Rohmer + 2013 Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
/**
 * LoginPage
 *
 * Lets a user log in.
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
class LoginPage extends Page {
    /**
     * Create Login Page
     *
     * @author Thibaud Rohmer
     */
    public function __construct() {
    }
    /**
     * Display Login Page on website
     *
     * @return void
     * @author Thibaud Rohmer
     */
    public function toHTML() {
        if (Settings::$forcehttps && (!isset($_SERVER["HTTPS"]) || !$_SERVER["HTTPS"]) && (!isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) || ($_SERVER["HTTP_X_FORWARDED_PROTO"] != "https"))) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://" . Settings::$self_url . Settings::$self_path . $_SERVER["REQUEST_URI"]);
            exit();
        } else {
            $this->header();
            echo "<div class='center'>\n";
            echo "<form method='post' action='?t=Log' class='niceform'>\n";
            echo "<div class='section'><h2>" . Settings::_("login", "logintitle") . "</h2>";
            /// Login
            echo "<fieldset>
                <div class='fieldname'>
                    <span>" . Settings::_("login", "login") . "</span>
                </div>
                <div class='fieldoptions'>
                    <input type='text' name='login' value=''>
                </div>
            </fieldset>\n";
            /// Password
            echo "<fieldset>
                <div class='fieldname'>
                    <span>" . Settings::_("login", "pass") . "</span>
                </div>
                <div class='fieldoptions'>
                    <input type='password' name='password' value=''>
                </div>
            </fieldset>\n";
            echo "<fieldset class='alignright'><input type='submit' value='" . Settings::_("login", "submit") . "' > " . Settings::_("login", "or");
            if (!Settings::$noregister) {
                echo " <a class='inline' href='?t=Reg'>" . Settings::_("login", "register") . "</a> " . Settings::_("login", "or");
            }
            echo " <a class='inline' href='.'>" . Settings::_("login", "back") . "</a>";
            echo "</fieldset></form>\n";
            echo "</div>\n";
        }
    }
}
?>
