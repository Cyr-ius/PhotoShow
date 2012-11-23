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
		<div class='modaladmin fade hide' id='ModalAdmin' tabindex='-1' role='dialog' aria-labelledby='ModalAdminLabel' aria-hidden='true'>
		<div class='modal-header'>
		<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>";
		$p = new AdminMenu();
		$p->toHTML();
		echo "		
		<h3 id='ModalAdminLabel'></h3>
		</div>
		<div class='modal-body'></div>
		<div class='modal-footer'><div class='alert alert-error modal-infos hide'></div></div>
		</div>";
	}	
	
}
?>