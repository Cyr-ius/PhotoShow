<?
class WS_Account
{
	function __construct(){
		Settings::init();
		CurrentUser::init();
	}
		
	public function create($login, $password, $verif, $groups=array(),$name='',$email=''){
		return Account::create($login, $password, $verif, $groups,$name,$email);
	} 

	public function register($login, $password, $verif, $groups=array(),$name='',$email=''){
		if (Account::create($login, $password, $verif, $groups,$name,$email)) {
			return self::login($login,$password);
		}
	} 

	public function edit($login=NULL,$name=NULL, $email=NULL, $language=NULL, $key=null,$password=NULL,$old_password=NULL,$groups=array()){
		return Account::edit($login, $old_password, $password, $name, $email, $groups, $language);
	}

	public function delete($login){
		return Account::delete($login);		
	} 
	
	public function get($login=null,$key=null){
		return Account::get_acc($login);
	}

	public function add_group($login,$group){
		return Account::add_group($login,$group);
	} 
	
	public function remove_group($login,$group){
		return Account::remove_group($login,$group);
	} 	

	public function exists($login){
		return Account::exists($login);		
	} 

	public function login($login,$password){
		if(CurrentUser::login($login,$password)){
			return CurrentUser::$account->get_key();
		}else{
			throw new jsonRPCException('User or password incorrect.');
		}
	}
	public function logout() {
		return CurrentUser::logout();
	}

	public function change_password($login,$old_password,$password){
		$rslt = Account::change_password($login,$old_password,$password);
		if ($rslt){
			return true;
		} else {
			throw new jsonRPCException('Insufficient Rights or Password incorrect');
		}		
	}
}
?>