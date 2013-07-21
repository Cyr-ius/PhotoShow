<?php
/**
<<<<<<< HEAD:src/classes/Json.php
 * This file implements the class JS.
 * 
=======
 * This file contains the website configuration for unit test only.
 * Do not modify !
 *
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4:src/tests/test_config.php
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
<<<<<<< HEAD:src/classes/Json.php

/**
 * JS Files
 *
 * Form for editing files. With JS.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class Json
{
	public static $json;
	
	public function __construct(){
	}
=======
// Folder where your pictures are stored.
// Must be at least readable by web server process
#$config->photos_dir   = "path_to_your_photos_dir_goes_here";
$config->photos_dir = realpath(dirname(__FILE__)) . "/tmp/photos/";
// Folder where PhotoShow parameters and thumbnails are stored.
// Must be writable by web server process
#$config->ps_generated   = "path_where_photoshow_generates_files_goes_here";
$config->ps_generated = realpath(dirname(__FILE__)) . "/tmp/generated/";
// Local timezone. Default one is "Europe/Paris".
#$config->timezone = "Europe/Paris";
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4:src/tests/test_config.php

	public function toHTML(){
		header('Content-Type: application/json');
		echo json_encode(Json::$json);
	}
}
?>
