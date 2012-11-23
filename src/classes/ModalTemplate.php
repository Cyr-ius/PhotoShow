<?php 
class ModalTemplate
{

	public function __construct() {
	}
	
	public function toHTML(){
		echo "
		<!-- Modal Template
		================================================== -->
		<div class='modal fade hide' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-header'>
		<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>
		<h3 id='myModalLabel'></h3>
		</div>
		<div class='modal-body'></div>
		<div class='modal-footer'><div class='alert alert-error modal-infos hide'></div></div>
		</div>";
	}
}
?>