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
		<div class='modal-header label-inverse modal-header-bar'>
		<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>
		<h3 id='myModalLabel' class='muted'></h3>
		</div>
		<div class='modal-body'></div>
		<!--div class='modal-footer'></div-->
		</div>";
	}
}
?>