<?php 
class ModalAdmin
{

	public function __construct() {
 		$this->options['Abo']	= Settings::_("adminmenu","about");
 		$this->options['Sta']	= Settings::_("adminmenu","stats");
 		$this->options['VTk']	= Settings::_("adminmenu","tokens");
 	 	$this->options['Set']	= Settings::_("adminmenu","settings");
 	 	$this->options['Acc']	= Settings::_("adminmenu","account");
 	 	$this->options['EdA']	= Settings::_("adminmenu","groups");
 	 	$this->options['UpM']	= Settings::_("adminmenu","uploadmgmt");	

	}
	
	public function toHTML(){
		if(!CurrentUser::$admin) {
			return;
		}
		echo "
		<!-- Modal Template
		================================================== -->
		<div  id='ModalAdmin' class='modal container fade hide' tabindex='-1' data-backdrop='static' data-keyboard='false'>\n
		<div  class='modal-header navbar'>\n
		<div class='navbar-inner modal-header-bar'>\n
			<div class='container-fluid '>\n
				<a class='btn btn-navbar' data-target='.admin.nav-collapse' data-toggle='collapse'>\n
				<span class='icon-bar'></span>\n
				<span class='icon-bar'></span>\n
				<span class='icon-bar'></span>\n
				</a>\n
				<div class='admin nav-collapse collapse'>\n
				\t<ul  class='nav'>\n";
				$list = new ModalAdmin();
				foreach($list->options as $op=>$val){
				echo "\t\t<li ><a style='text-decoration:none' id='$op' data-target='#ModalAdmin' data-toggle='modaladmin' data-href='?t=Adm&a=$op'>$val</a></li>\n";
				}
				echo "\t</ul>\n
				<ul class='nav pull-right'  style='margin-right: -30px;'><li><a data-dismiss='modal' aria-hidden='true' href='#'>X</a></li></ul>
				</div>\n
			</div>\n
		</div>\n
		</div>\n
		<div class='modal-body'></div>\n
		<div class='modal-footer'></div>\n
		</div>\n";
	}	
	
}
?>
