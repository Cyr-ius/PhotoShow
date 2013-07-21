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

class TextInfo
{
	
	public $title=null;
	public $author=null;
	public $contain=null;

	/**
	 * Create a Judge for a specific file.
	 *
	 * @param string $f 
	 * @param string $read_rights 
	 * @author Thibaud Rohmer
	 */
	public function __construct($f){
		
		if(!file_exists($f)){
			return;
		}
		$this->file = $f;
		self::Get_File($f);
		self::Get_Contains();
	}
	
	/**
	 */
	private function Get_File($f){
				
		$basefile	= 	new File($f);
		$basepath	=	File::a2r($f);

		$this->filename = $basefile->name;
		$this->webpath 	= urlencode($basepath);

		if(is_file($f)){
			$textfile	=	dirname($basepath)."/.".basename($f)."_textexplain.xml";
		}else{
			$textfile	=	$basepath."/.textexplain.xml";
		}
		$this->path =	File::r2a($textfile,Settings::$thumbs_dir);

	}
	
	/**
	 */
	private function Get_Contains(){
		if (is_file($this->path))  {
			$xml			=	simplexml_load_file($this->path);
			$this->title		=	htmlspecialchars($xml->title, ENT_QUOTES ,'UTF-8');
			$this->author 	= 	htmlspecialchars($xml->author, ENT_QUOTES ,'UTF-8');
			$this->contain	= 	htmlspecialchars($xml->contain, ENT_QUOTES ,'UTF-8');
		}
	}

	/**
	 */
	public static function Save_File($f ,$title=null,$author=null,$contain=null){
		
		$ti = new TextInfo($f);
		
		/// Create xml
		$xml		=	new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><infos></infos>');
		
		/// Put values in xml
		$xml->addChild('title',$title);
		$xml->addChild('author',$author);
		$xml->addChild('contain',$contain);
		
		if(!file_exists(dirname($ti->path))){
			@mkdir(dirname($ti->path),0755,true);
		}
		/// Save xml
		$xml->asXML($ti->path);
		Json::$json = array("action"=>"TextInfo",
					"result"=>0,
					"uri"=>urlencode(File::a2r(CurrentUser::$path)),						
					"desc"=>"Save information for album");					
		return;		
	}
	
	public static function Delete_File($f) {
	
		/// Just to be sure, check that user is admin
		if(!CurrentUser::$admin)
			return;
	
		$ti = new TextInfo($f);
		if(file_exists($ti->path)){
			@unlink($ti->path);
		}
		Json::$json = array("action"=>"TextInfo",
					"result"=>0,
					"uri"=>urlencode(File::a2r(CurrentUser::$path)),						
					"desc"=>"Delete information for album");					
		return;			
	
	}
	
	
	public static function Edit_File($f) {

		/// Just to be sure, check that user is admin
		if(!CurrentUser::$admin)
			return;

		$ti = new TextInfo($f);
		if (!isset($ti->author)) { $ti->author = CurrentUser::$account->login; }
		
		echo "<form id='editti-form' class='form-horizontal' action='?f=".File::a2r($f)."&t=Adm&a=Tis' method='post'>\n";
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
			<input id='f' type='hidden' name='f' value='$f' />\n";
		echo "</fieldset>\n";
		echo "</form>\n";
		echo "<form id='delti-form' class='form-horizontal' action='?f=".File::a2r($f)."&a=Tid' method='post'>\n
			<input id='f' type='hidden' name='f' value='$f' />\n
			<input id='button_clean'  type='submit'  class='btn btn-warning' value='".Settings::_("textinfo","delete")."' data-loading-text='Deleting...'/>
			</form>\n";
	}
	
	/**
	 */
	public function toHTML(){
		
		if(CurrentUser::$admin) {
			echo "<div  class='well textinfoadmin'>\n";
			self::Edit_File($this->file);
			echo "</div>\n";
		}
		
		if (is_file($this->path) && !empty($this->contain) )  {
			echo "<div  class='well textinfo'>\n";
			echo "<span>".nl2br($this->contain)."<p style='font-size:12px;text-align:right'>$this->author</p></span>";
			echo "</div>\n";
		}
	}
}
?>
