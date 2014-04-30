<?php
class WS_Account
{
	function __construct(){
		Settings::init();
		CurrentUser::init();
	}
		
	public function create($variables){
		return Account::create($variables['login'], $variables['password'], $variables['verif'], $variables['groups'],$variables['name'],$variables['email']);
	} 

	public function register($variables){
		if (Account::create($variables['login'], $variables['password'], $variables['verif'], $variables['groups'],$variables['name'],$variables['email'])) {
			return self::login($variables);
		}
	} 

	public function edit($variables){
		return Account::edit($variables['login'], $variables['old_password'], $variables['password'], $variables['name'],$variables['email'],$variables['groups'],$variables['language']);
	}

	public function delete($variables){
		return Account::delete($variables['name']);		
	} 
	
	public function get($variables){
		return Account::get_acc($variables['login']);
	}

	public function add_group($variables){
		return Account::add_group($variables['login'],$variables['group']);
	} 
	
	public function remove_group($variables){
		return Account::remove_group($variables['name'],$variables['groupname']);
	} 	

	public function exists($login){
		return Account::exists($login);		
	} 

	public function login($variables){
		if(CurrentUser::login($variables['login'], $variables['password'])){
			//~ return CurrentUser::$account->get_key();
			return;
		}else{
			throw new jsonRPCException('User or password incorrect.');
		}
	}
	public function logout() {
		return CurrentUser::logout();
	}

	public function change_password($variables){
		$rslt = Account::change_password($variables['login'], $variables['old_password'], $variables['password']);
		if ($rslt){
			return true;
		} else {
			throw new jsonRPCException('Insufficient Rights or Password incorrect');
		}		
	}
}
?>