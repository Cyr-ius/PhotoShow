<?php 
class ModalAdmin
{

	public function __construct() {

	}
	
	public function toHTML(){
		if(!CurrentUser::$admin) {
			return;
		}
		echo "
		<!-- Modal Template
		================================================== -->
		<div  id='ModalAdmin' class='modal container fade hide' tabindex='-1' data-backdrop='static' data-keyboard='false'>
		<div  class='modal-header navbar'>
		<div class='navbar-inner modal-header-bar'>
			<div class='container-fluid '>
				<a class='btn btn-navbar' data-target='.nav-collapse' data-toggle='collapse'>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				</a>
				<div class='nav-collapse collapse'>";
				$p = new AdminMenu();
				$p->toHTML();
				echo "
				<ul class='nav pull-right'  style='margin-right: -30px;'><li><a data-dismiss='modal' aria-hidden='true' href='#'>X</a></li></ul>
				</div>
			</div>
		</div>
		</div>
		<div class='modal-body'></div>
		<div class='modal-footer'></div>
		</div>";
	}	
	
}
?>