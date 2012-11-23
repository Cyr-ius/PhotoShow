<?php
/**
 * This file implements the class Account.
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
 * @author	  Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license	  http://www.gnu.org/licenses/
 * @link	  http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * Account
 *
 * Implements functions to work with a User account.
 * Read the account from the Accounts XML file,
 * edit it, and save it.
 * 
 * Account Structure:
 * - Login
 * - Name
 * - Password (Encryption : sha1)
 * - Email
 * - Groups -> Group names
 * - Language
 * - Key
 *
 * 
 * @package	  PhotoShow
 * @category  Website
 * @author	  Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license	  http://www.gnu.org/licenses/
 * @link	  http://github.com/thibaud-rohmer/PhotoShow
 */
class Account extends Page
{
	/// Login of the user
	public $login;

	/// Password of the user, encrypted via sha1
	public $password;

	/// Name of the user (optional)
	public $name;

	/// Email of the user (optional)
	public $email;

	/// Language of the user (optional)
	public $language;

	/// Key of the user (optional)
	public $key;

	/// List of the groups the user is in. No duplicates. Minimum list : array("user")
	public $groups;
	
	/**
	 * Find account in the base.
	 *
	 * @param string $login 
	 * @author Thibaud Rohmer
	 */
	public function __construct($login=NULL,$key=NULL){
		if(!isset($login) && !isset($key)) {
			Json::$json = array("action"=>"Account",
				"result"=>1,
				"desc"=>"Login not found");			
			return false;
		}
		$xml_infos	=	CurrentUser::$accounts_file;
		$xml		=	simplexml_load_file($xml_infos);

		// Look each account
		foreach( $xml as $account ){
			if((string)$account->login == $login || (isset($key) && $key != '' && (string)$account->key == $key)){
				$this->login	= (string)$account->login;
				$this->password = (string)$account->password;
				$this->name		= (string)$account->name;
				$this->email	= (string)$account->email;
				$this->language	= (string)$account->language;
				$this->key		= (string)$account->key;

				$this->groups 	= array();

				foreach($account->groups->children() as $group){
					$this->groups[] = (string)$group;
				}	
				Json::$json = array("action"=>"Account",
					"result"=>0,
					"desc"=>"Operation on ".$login." account sucessfull");					
				return true;
			}
		}
		//throw new Exception("Login $login not found");		
		Json::$json = array("action"=>"Account",
			"result"=>1,
			"desc"=>"Login not found");				
		return false;
	}
	
	/**
	 * Creates a new account in the base
	 *
	 * @param string $login 
	 * @param string $password 
	 * @author Thibaud Rohmer
	 */ 
	public static function create($login, $password, $verif, $groups=array(),$name='',$email=''){
		// Check if login already exists
		if(Account::exists($login) || (!CurrentUser::$admin && Settings::$noregister) || $password != $verif) {
			Json::$json = array("action"=>"Account",
				"result"=>1,
				"desc"=>"Error : No Rights or Syntax error");
			return false;
		}
		// All users belong to the "user" group
		$groups[]="user";

		$xml_infos=CurrentUser::$accounts_file;
		
		if(!file_exists($xml_infos) || sizeof(Account::findAll()) == 0 ){
			// No account

			// Create accounts file
			$xml	=	new SimpleXMLElement('<accounts></accounts>');
			$xml->asXML($xml_infos);
			
			// Set this account as root
			$groups[] = "root";
		}


		if( preg_match("/[^a-z0-9]/i", $login) || strlen($password) < 6){
			Json::$json = array("action"=>"Account",
				"result"=>1,
				"desc"=>"Error : Account (a-z et 0-9) or password inccorect (length > 6)");				
			return false;
		}

		$acc			=	new Account();
		$acc->login		=	$login;
		$acc->password	=	Account::password($password);
		$acc->groups		=	$groups;
		$acc->name		=	$name;
		$acc->email		=	$email;
		$acc->language 	=	"";
		$acc->key 		=	"";
		$acc->save();
		Json::$json = array("action"=>"Account",
			"result"=>0,
			"desc"=>"Create Account for ".$login." sucessfull");					
		return true;
	}
	
	/**
	 * Encrypt password
	 *
	 * @param string $password 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function password($password){
		return sha1($password);
	}
		
	/**
	 * Generate key
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	private function key(){
		$salt = sha1(rand());
		$salt = substr($salt, 0, 4);
		return substr(sha1($this->password.$salt),0,5);
	}


	public function get_key(){
		if(!isset($this->key) || $this->key == ''){
			$this->key = $this->key();
			$this->save();
		}

		return $this->key;
	}

	/**
	 * Add a group to this user
	 *
	 * @param string $group 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function add_group($group){
		// Check that this user doesn't already belong to this group
		if(!in_array($group,$this->groups)){
			$this->groups[]=$group;
			$g = new Group($group);
			$g->save();
		}
	}
	
	/**
	 * Remove a group from this user
	 *
	 * @param string $group 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function remove_group($group){
		// Check that this user belongs to this group
		if(in_array($group,$this->groups)){
			$id=array_search($group,$this->groups);
			unset ( $this->groups[$id] );
		}
	}

	/**
	 * Save account in the base
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function save(){

		$xml_infos	=	CurrentUser::$accounts_file;
		$xml		=	simplexml_load_file($xml_infos);
			
		foreach( $xml as $acc ){
			if((string)$acc->login == $this->login){
				$account=$acc;
				break;
			}
		}

		if(isset($account)){		
			$account->password	=	$this->password;
			$account->name		=	$this->name;
			$account->email		=	$this->email;
			$account->language 	=	$this->language;
			$account->key 		=	$this->key;
			unset($account->groups);
		}else{
			$account=$xml->addChild('account');
			$account->addChild(		'login' ,		$this->login);
			$account->addChild(		'password', $this->password);
			$account->addChild(		'name'	,		$this->name);
			$account->addChild(		'email' ,		$this->email);
			$account->addChild(		'language' ,		$this->language);
			$account->addChild(		'key' ,		$this->key);
		}
		
		// Create the groups
		$groups = $account->addChild('groups');
		foreach($this->groups as $group){
			$groups->addChild('group',$group);
			
			try{
				$g	=	new Group($g);
				$g->save();
			}catch(Exception $e){
				// This group already exists
			}
		}
		// Saving into file
		$xml->asXML($xml_infos);
	}

	/**
	 * Edit an account
	 * 
	 * @param string $login
	 * @param string $old_password
	 * @param string $password
	 * @param string $name
	 * @param string $email
	 * @author Thibaud Rohmer
	 */
	public static function edit($login=NULL, $old_password=NULL, $password=NULL, $name=NULL, $email=NULL, $groups=array(), $language=NULL){
		/// Only the admin can modify other accounts
		if( !CurrentUser::$admin && $login != CurrentUser::$account->login ){
			Json::$json = array("action"=>"Account",
				"result"=>1,
				"desc"=>"Error : No Rights for ".$login);	
			return;
		}

		if(isset($login) && (preg_match("/[^a-z0-9]/i", $login) === 0) ){
			$acc = new Account($login);
		}else{
			$acc = CurrentUser::$account;
		}
		
		/// Check password
		if (!CurrentUser::$admin && Account::password($old_password) != $acc->password )  {
			Json::$json = array("action"=>"Account",
				"result"=>1,
				"desc"=>"Error : New and old password required ");	
			return;
		}

		/// Edit attributes
		if(isset($password) && sizeof($password) > 4 ){
			$acc->password = Account::password($password);
		}

		if(isset($name)){
			$acc->name = $name;
		}

		if(isset($email)){
			$acc->email = $email;
		}


		if(isset($language)){
			//~ echo "woot";
			$acc->language = $language;
		}

		if(CurrentUser::$admin && sizeof($groups) > 0){
			$acc->groups = $groups;
		}

		/// Save account
		$acc->save();
		Json::$json = array("action"=>"Account",
			"result"=>0,
			"desc"=>"Save Account for ".$login." sucessfull");			
		return;				
	}
	
	/**
	 * Delete an account
	 *
	 * @param string $login 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function delete($login){
		$xml_infos 	=	CurrentUser::$accounts_file;
		$xml		=	simplexml_load_file($xml_infos);
		
		$i=0;
		foreach( $xml as $acc ){
			if((string)$acc->login == $login){
				unset($xml->account[$i]);
				break;
			}
		$i++;
		}
		$xml->asXML($xml_infos);
	}

	/**
	 * Check if an account already exists
	 *
	 * @param string $login
	 * @return bool
	 * @author Thibaud Rohmer
	 */
	public static function exists($login){

		// Check if the accounts file exists
		if(!file_exists(CurrentUser::$accounts_file)){
			return false;
		}
		$xml_infos	=	CurrentUser::$accounts_file;
		$xml		=	simplexml_load_file($xml_infos);

		foreach( $xml as $account ){
			if((string)$account->login == $login)
				return true;
		}
	
		return false;
	}
	
	
	public function get_acc(){			
		$acc=array();
		$acc['login']		= $this->login;
		$acc['password']	= $this->password;
		$acc['name']		= $this->name;
		$acc['email']		= $this->email;
		$acc['language']	= $this->language;
		$acc['key']			= $this->key;
		$acc['groups']		= $this->groups;

		return $acc;
	}

	/**
	 * Returns an array containing all accounts
	 *
	 * @return array $accounts
	 * @author Thibaud Rohmer
	 */
	public static function findAll(){
		$accounts	=	array();
		
		$xml_infos	=	CurrentUser::$accounts_file;
		$xml		=	simplexml_load_file($xml_infos);

		foreach( $xml as $account ){
			$new_acc=array();
			
			$new_acc['login']		= $account->login;
			$new_acc['password']	= $account->password;
			$new_acc['name']		= $account->name;
			$new_acc['email']		= $account->email;
			$new_acc['language']	= $account->language;
			$new_acc['key']			= $account->key;
			$new_acc['groups']		= array();
			foreach($account->groups->children() as $group){
				$new_acc['groups'][]= $group;
			}

			$accounts[]=$new_acc;
		}
		
		return $accounts;
	}
	
	/**
	 * Returns the rights of an account
	 *
	 * @param string $login 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function rights($login){
		$rights =	array();

		$xml_infos	=	CurrentUser::$accounts_file;
		$xml		=	simplexml_load_file($xml_infos);

		foreach( $xml as $account ){
			if($account->login==$login){
				foreach($account->groups->children() as $group){
					$rights=array_unique(array_merge($rights,Group::rights($group)));
				}
			}
		}

		return $rights;
	}
	 
	 public function toHTML($addUser=null) {
	 
		if ($addUser) {
			echo "<form id='adminaccount-form' class='form-horizontal' method='post' action='?t=Adm&a=Acc'>\n";
		}else{
			echo "<form id='account-form' class='form-horizontal' method='post' action='?t=MyA'>\n";
		}	 
		echo "<legend>".Settings::_("account","account")."</legend>\n";		
		echo "<fieldset>\n";			
		/// Login
		echo "<div class='control-group'>\n";
		echo "<label for='login' class='control-label'>".Settings::_("account","Login")."</label>";
		echo "<div class='controls'><input id='login' class='input-large' type='text' name='login' readonly='readonly' value='".htmlentities($this->login, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";
		/// Name
		echo "<div class='control-group'>\n";
		echo "<label for='name' class='control-label'>".Settings::_("account","name")."</label>";
		echo "<div class='controls'><input id='name' class='input-large' type='text' name='name' value='".htmlentities($this->name, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";		
		/// Email
		echo "<div class='control-group'>\n";
		echo "<label for='email' class='control-label'>".Settings::_("account","email")."</label>";
		echo "<div class='controls'><input id='email' class='input-large' type='text' name='email'  value='".htmlentities($this->email, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";			
		/// Language
		echo "<div class='control-group'>\n";
		echo "<label for='language' class='control-label'>".Settings::_("account","language")."</label>";
		echo "<div class='controls'><select id='language' name='language' class='input-large'>";
			foreach(Settings::$ava_loc as $l){
				$p = substr(htmlentities($l, ENT_QUOTES ,'UTF-8'),0,-4);
				echo "<option value=\"".addslashes($p)."\"";
				if($p == $this->language){
					echo " selected='selected' ";
				}
				echo ">".$p."</option>";
			}			
		echo "</select></div>\n";
		echo "</div>\n";	
		/// Key
		echo "<div class='control-group'>\n";
		echo "<label for='Key' class='control-label'>".Settings::_("account","key")."</label>";
		if ($addUser) {
			echo "<div class='controls'><input id='Key' class='input-large' type='text' name='key' readonly='readonly' value='".htmlentities($this->key, ENT_QUOTES ,'UTF-8')."'></div>\n";
		} else {
			echo "<div class='controls'><input id='Key' class='input-large' type='text' name='key' readonly='readonly' value='".htmlentities($this->get_key(), ENT_QUOTES ,'UTF-8')."'></div>\n";
		}
		echo "</div>\n";			
		/// Password
		echo "<div class='control-group'>\n";
		echo "<label for='password' class='control-label'>".Settings::_("account","password")."</label>";
		echo "<div class='controls'><input id='password' class='input-large' type='password' name='password'  value='".htmlentities($this->password, ENT_QUOTES ,'UTF-8')."'></div>\n";
		echo "</div>\n";
	 	if(CurrentUser::$admin){
	 		echo "<input type='hidden' value='plip' name='edit'>";
 		}else{
	 		/// Old Pass
			echo "<div class='control-group'>\n";
			echo "<label for='old_password' class='control-label'>".Settings::_("account","oldpass")."</label>";
			echo "<div class='controls'><input id='old_password' class='input-large' type='password' name='old_password'  value=''></div>\n";
			echo "</div>\n";				
 		}
		echo "<div class='controls controls-row'>\n";
		echo "<input  class='btn btn-primary' type='submit' value='".Settings::_("account","submit")."'>\n";
		echo "</div>\n";
		echo "</fieldset>\n";		
		echo "</form>\n";		
	 }	 

}


?>
