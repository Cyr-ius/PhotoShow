<?php
/**
 * This file implements the class TextInfo.
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
 * @author    Cédric Levasseur
 * @copyright 2011 Cédric Levasseur
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/cyr-ius/PhotoShow
 */

/**
 * TextInfo
 *
 *TextInfo contain explain text of Album
 *

 */

class TextInfo extends Page
{
	
	public $title=null;
	public $author=null;
	public $contain=null;


	public function __construct($f){
		
		if(!file_exists($f)){
			$f = CurrentUser::$path;
		}

		$rslt = self::get($f);
		$this->author = $rslt->author;
		$this->contain = $rslt->contain;
		$this->title = $rslt->title;
	}
	
	/**
	 */
	public function get_path($f){
				
		$basefile	= 	new File($f);
		$basepath	=	File::a2r($f);

		$filename = $basefile->name;
		$webpath 	= urlencode($basepath);

		if(is_file($f)){
			$textfile	=	dirname($basepath)."/.".basename($f)."_textexplain.xml";
		}else{
			$textfile	=	$basepath."/.textexplain.xml";
		}
		$path =	File::r2a($textfile,Settings::$thumbs_dir);
		return $path;

	}
	
	/**
	 */
	public function get($f){
		$path = self::get_path($f);
		if (is_file($path))  {
			$xml		=	simplexml_load_file($path);
			$txti->title	=	htmlspecialchars($xml->title, ENT_QUOTES ,'UTF-8');
			$txti->author	=	htmlspecialchars($xml->author, ENT_QUOTES ,'UTF-8');
			$txti->contain	=	htmlspecialchars($xml->contain, ENT_QUOTES ,'UTF-8');
			return $txti;
		} else {
			return false;
		}
	
	}

	/**
	 */
	public static function create($f ,$title=null,$author=null,$contain=null){
		
		$path = self::get_path($f);
		
		/// Create xml
		$xml		=	new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><infos></infos>');
		
		/// Put values in xml
		$xml->addChild('title',$title);
		$xml->addChild('author',$author);
		$xml->addChild('contain',$contain);
		
		if(!file_exists(dirname($path))){
			@mkdir(dirname($path),0755,true);
		}
		/// Save xml
		$xml->asXML($path);				
		return true;		
	}
	
	public static function delete($f) {
	
		/// Just to be sure, check that user is admin
		if(!CurrentUser::$admin)
			return false;
	
		$path = self::get_path($f);
		if(file_exists($path)){
			@unlink($path);			
			return true;				
		}  else { return false;}
		
	
	}
	
	
	public static function Edit_File($f) {

		/// Just to be sure, check that user is admin
		if(!CurrentUser::$admin)
			return;
		$ti = self::get($f);
		if (!isset($ti->author)) { $ti->author = CurrentUser::$account->login; }
		
		echo "<form id='editti-form' class='form-horizontal' action='WS_Textinfo.create' method='post'>\n";
		echo "<fieldset>\n";
		echo "<legend>Information</legend>\n
			<div class='control-group'>\n
			<label for='title' class='control-label'>".Settings::_("textinfo","title")."</label>\n
			<div class='controls'><input id='title' class='input-large' type='text' name='title' value='$ti->title' placeholder='".Settings::_("textinfo","title")."' /></div>\n
			</div>\n
			<div class='control-group'>\n
			<label for='author' class='control-label'>".Settings::_("textinfo","name")."</label>\n
			<div class='controls'><input id='author' class='input-large' type='text' name='author' value='$ti->author' /></div>\n
			</div>\n
			<div class='control-group'>\n
			<label for='contain' class='control-label'>".Settings::_("textinfo","explain")."</label>\n
			<div class='controls'><textarea id='contain' class='span12'  rows='4' name='contain'>$ti->contain</textarea></div>\n
			</div>\n
			<div class='controls controls-row'>\n
			<input id='button_submit' type='submit' class='btn btn-primary' data-loading-text='Posting...' value='".Settings::_("settings","submit")."' />\n
			</div>\n
			<input id='f' type='hidden' name='path' value='$f' />\n";
		echo "</fieldset>\n";
		echo "</form>\n";
		echo "<form id='delti-form' class='form-horizontal' action='WS_Textinfo.delete' method='post'>\n
			<input id='f' type='hidden' name='path' value='$f' />\n
			<input id='button_clean'  type='submit'  class='btn btn-warning' value='".Settings::_("textinfo","delete")."' data-loading-text='Deleting...'/>
			</form>\n";
	}
	
	/**
	 */
	public function toHTML(){
		
		 new TextInfo();
		if(CurrentUser::$admin) {
			echo "<div  class='well textinfoadmin'>\n";
			self::Edit_File(CurrentUser::$path);
			echo "</div>\n";
		}
		
		if ($this->contain)  {
			echo "<div  class='well textinfo'>\n";
			echo "<span>".nl2br($this->contain)."<p style='font-size:12px;text-align:right'>$this->author</p></span>";
			echo "</div>\n";
		}
	}
}
?>
