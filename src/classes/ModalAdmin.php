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
		<div class='modal-header'>
		<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>";
		$p = new AdminMenu();
		$p->toHTML();
		echo "		
		<h3></h3>
		</div>
		<div class='modal-body'></div>
		<div class='modal-footer'>
		<span class='alert alert-error modal-infos hide'></span>
		</div>
		</div>";
	}	
	
}
?>