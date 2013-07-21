<?php
/**
<<<<<<< HEAD:src/classes/Message.php
 * This file implements the class JS.
 * 
=======
 * This file implements unit tests for Settings class
 *
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4:src/tests/SettingsTest.php
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
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
/**
 * JS Files
 *
<<<<<<< HEAD:src/classes/Message.php
 * Form for editing files. With JS.
=======
 * I used that for some debug. It's incomplete and I guess
 * It would be better to have a proper framework for unit
 * test on PHP website. Anyway, it does not harm anyone for now
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4:src/tests/SettingsTest.php
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
<<<<<<< HEAD:src/classes/Message.php
class Message
{
	public function __construct(){
	}

	public function toHTML(){
		echo "
		<div id='m1' class='container-fluid message alert alert-error hide'>
		<span class='message_txt'></span>
		</div>
		<div id='m2' class='container-fluid message alert alert-warning hide'>
		<span class='message_txt'></span>
		</div>
		<div id='m0' class='container-fluid message alert alert-info hide'>
		<span class='message_txt'></span>
		</div>
		";
	}
=======
require_once (realpath(dirname(__FILE__) . "/TestUnit.php"));
class SettingsTest extends TestUnit {
    /**
     * test set_lang
     * @test
     */
    public function test_set_lang() {
        Settings::set_lang("francais");
        $this->assertEquals(Settings::_("settings", "noregister"), "Bloquer les inscriptions");
    }
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4:src/tests/SettingsTest.php
}

?>
