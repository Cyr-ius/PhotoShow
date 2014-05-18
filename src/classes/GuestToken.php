<?php
/**
 * This file implements the class Guest Token
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package	  PhotoShow
 * @category  Website
 * @author	  Franck Royer <royer.franck@gmail.com>
 * @author	  Franck Royer <thibaud.rohmer@gmail.com>
 * @copyright 2012 Franck Royer
 * @license	  http://www.gnu.org/licenses/
 * @link	  http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * Account
 *
 * Implements functions to work with a Guest Token (or key)
 * Read the account from the Guest Token XML file,
 * edit it, and save it.
 * 
 * 
 * @package	  PhotoShow
 * @category  Website
 * @author	  Franck Royer <royer.franck@gmail.com>
 * @author	  Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license	  http://www.gnu.org/licenses/
 * @link	  http://github.com/thibaud-rohmer/PhotoShow
 */
class GuestToken extends Page
{
    /// Value of the key
    public $key;

    /// Path this key allows access to
    public $path;

    public function __construct($f=null){
	$this->file 	=	$f;
    }

    /**
     * Creates a new token in the base
     *
     * @param string $key 
     * @param array  $path 
     * @author Franck Royer
     */ 
    public static function create($path, $key = NULL){

        // A token with no path is useless
        // Only admin can create a token for now
        if(!isset($path) || !CurrentUser::$admin){
            throw new jsonRPCException('Insufficient Rights');
        }

        if (!isset($key)){
            $key = self::generate_key();
        }

        if (self::exist($key)){	    
            throw new jsonRPCException('Error : GuestToken: Key '.$key.' already exist, aborting creation');                                
        }

        if(!file_exists(CurrentUser::$tokens_file) || sizeof(self::findAll()) == 0 ){
            // Create file
            $xml	=	new SimpleXMLElement('<tokens></tokens>');
            $xml->asXML(CurrentUser::$tokens_file);
        }

        // I like big keys
        if( strlen($key) < 10){
            throw new jsonRPCException('Error : GuestToken: Key '.$key.' is too short.');                                
        }

        $token			=	new GuestToken();
        $token->key     =   $key;
        $token->path	=	File::a2r($path);
        $token->save();
        return true;
    }

    /**
     * Save token in the base
     *
     * @return void
     * @author Franck Royer
     */
    private function save(){
        // For now we do not allow an edit on tokens

        $xml		=	simplexml_load_file(CurrentUser::$tokens_file);

        if (self::exist($this->key)){
            //We cannot change an existing key
            return false;
        }

        $xml_token=$xml->addChild('token');
        $xml_token->addChild('key' ,$this->key);
        $xml_token->addChild('path' ,$this->path);

        // Saving into file
        $xml->asXML(CurrentUser::$tokens_file);
    }

    /**
     * Delete a token
     *
     * @param string $key 
     * @return void
     * @author Franck Royer
     */
    public static function delete($key){
        if (!CurrentUser::$admin || !file_exists(CurrentUser::$tokens_file)){
            // Only admin can delete the tokens for now
	    throw new jsonRPCException('Insufficient Rights');
        }

        $xml		=	simplexml_load_file(CurrentUser::$tokens_file);

        $i=0;
        $found = false;
        foreach( $xml as $tk ){
            if((string)$tk->key == $key){
                unset($xml->token[$i]);
                $found = true;
                break;
            }
            $i++;
        }

        if ($found && $xml->asXML(CurrentUser::$tokens_file)){
            return true;
        } else {
            throw new jsonRPCException('Token not found');
        }
    }

    /**
     * Check if a token already exists
     *
     * @param string $key
     * @return bool
     * @author Franck Royer
     */
    public function exist($key){
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
		return false;
        }

        $xml		=	simplexml_load_file(CurrentUser::$tokens_file);
        foreach( $xml as $token ){
            if((string)$token->key == (string)$key)
                return true;
        }
        return false;
    }


    /**
     * Returns an array containing all tokens
     *
     * @return array $tokens, False if not found
     * @author Franck Royer
     */
    public static function findAll(){
        $tokens	=	array();
        
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
		return false;
        }

        $xml		=	simplexml_load_file(CurrentUser::$tokens_file);
        foreach( $xml as $token ){
            $new_token=array();
            $new_token['key']	= (string)$token->key;
            $new_token['path']	= (string)$token->path;
            $tokens[]=$new_token;
        }
        return $tokens;
    }

    /**
     * Returns an array containing all tokens
     * which has access to the given path
     *
     * @param string $path
     * @return array $tokens, False if not found
     * @author Franck Royer
     */
    public static function find_for_path($path, $exact_path = false){
        $tokens	=	array();
        
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
		return false;
        }

        foreach( self::findAll() as $token ){
            if ($exact_path){
                if ($token['path'] == $path){
                    $tokens[]=$token;
                }
            } else {
                if (self::view($token['key'], $path)){
                    $tokens[]=$token;
                }
            }
        }
        return $tokens;
    }
    
    /**
     * Returns an array containing all tokens
     * contain in path
     *
     * @param string $path
     * @return array $tokens, False if not found
     * @author Franck Royer
     */
    public static function find_for_contain($path){
        $tokens	=	array();
        
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
		return false;
        }

        foreach( self::findAll() as $token ){
                if (dirname($token['path']) == $path || $token['path'] == $path){
                    $tokens[]=$token;
                }
        }
        return $tokens;
    }    

    /**
     * Returns the allowed path of a guest token
     *
     * @param string $key 
     * @return path, False if not found
     * @author Franck Royer
     */
    public static function get_path($key){
        $path = "";
        
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
            //~ throw new jsonRPCException('Token not exists');
	    return false;
        }

        $xml		=	simplexml_load_file(CurrentUser::$tokens_file);

        foreach( self::findAll() as $token ){
            if((string)$token['key'] == (string)$key){
                $path = $token['path'];
                break;
            }
        }

        return $path;
    }

    /**
     * Returns the url to use a token
     * 
     * @param string $key 
     * @return url, False if not found
     * @author Franck Royer
     */
    public static function get_url($key){
        $url = "";
        
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
            //~ throw new jsonRPCException('Token not exists');
	    return false;
        }

        if (self::exist($key)){
            $url = Settings::$site_address."?f=".urlencode(self::get_path($key))."&token=".$key;
        }

        return $url;
    }

    /**
     * Returns true if the token is allowed to view the file
     * in the given path
     *
     * @param string $key
     * @param string $path
     * $return bool
     * @author Franck Royer
     */
    public static function view($key,$path){
        $rpath = File::a2r($path)."/";
        $apath = self::get_path($key)."/";

        // Remove double slashes
        preg_replace('/\/\/+/','/', $rpath);
        preg_replace('/\/\/+/','/', $apath);

         
        // Check if the tokens file exists
        if(!file_exists(CurrentUser::$tokens_file)){
            //~ throw new jsonRPCException('Token not exists');
            return false;
        }

        if (!$apath || !$rpath){
            //~ throw new jsonRPCException('Token path not exists');
            return false;
        }

        if(preg_match("/^".preg_quote($apath, '/')."/", $rpath)){
            return true;
        }
        //~ throw new jsonRPCException('Token path not exists');
        return false;

    }


    /**
     * Generate a new key
     *
     * @return generated key
     * @author Franck Royer
     */
    public static function generate_key(){
        $key = sha1(uniqid(rand(), true));
        return $key;
    }


    /**
     * Display a list of existing tokens
     * 
     */
	public function toHTML() {
	
	echo "<div class='row-fluid'>\n";
	echo "<form id='createtoken-form' action='WS_Token.create' method='post'>\n
		<fieldset>\n";
		$tokens = GuestToken::find_for_path($this->file);
		if ($tokens && !empty($tokens)){
			foreach($tokens as $token){
			echo "
			<div class='controls controls-row'>\n
			<a href='".GuestToken::get_url($token['key'])."' >".$token['key']."</a><br />\n
			</div>";
			}
		}
	echo "<div class='controls controls-row'>\n
		<input type='hidden' name='path' value='".CurrentUser::$path."'/>\n
		<input type='submit' class='btn btn-primary' value='".Settings::_("token","createtoken")."' />\n
		</div>\n
		</fieldset>\n
		</form>";	
	echo "</div>\n";
		
	}    

}

?>
