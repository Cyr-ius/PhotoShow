<?php 
class ModalTemplate
{

	public function __construct() {
	}
	
	public function toHTML(){
		echo "
		<!-- Modal Template
		================================================== -->
		<div  id='myModal' class='modal fade hide' tabindex='-1' data-focus-on='input:first'>
		<div class='modal-header'>
		<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>
		<h3 id='myModalLabel'></h3>
		</div>
		<div class='modal-body'></div>
		<div class='modal-footer'><span class='alert alert-error modal-infos hide'></span></div>
		</div>";
	}
}
?>