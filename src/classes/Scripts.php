<?php 
class Scripts
{

	public function __construct() {
	}
	
	public function toHTML(){

		echo "
		<!-- Le javascript
		================================================== -->
		
		<!-- Framwork JQUERY 1.8 -->
		<script src='inc/jquery-1.8.3.min.js'></script>
		<script src='inc/jquery-ui-1.9.1.custom.min.js'></script>
		
		<!-- Framework BootStrap -->
		<script src='bootstrap/js/bootstrap.min.js'></script>
		<script src='bootstrap/js/bootstrap-modalmanager.js'></script>
		<script src='bootstrap/js/bootstrap-modal.js'></script>

		
		<!-- PLUpload -->
		<script src='plupload/js/plupload.full.js'></script>
		<script src='plupload/js/jquery.plupload.queue/jquery.plupload.queue.js'></script>
		
		<!-- API Fullscreen for slideshow -->
		<script src='inc/jquery.fullscreen.js'></script>
		
		<script src='inc/mousewheel.js'></script>		
		<script src='inc/jquery.scrollTo.js'></script>
		<script src='inc/jquery.scroll.js'></script>
		<script src='src/js/panel.js'></script>
		<script src='src/js/menu.js'></script>		
		<script src='src/js/image_panel.js'></script>
		<script src='src/js/slideshow.js'></script>
		<script src='src/js/keyboard.js'></script>

		<script src='inc/jquery.scrollstop.js'></script>
		<script src='inc/jquery.lazyload.min.js'></script>				
		<script>
		      !function ($) {
			$(function(){
			  // Fix for dropdowns on mobile devices
			  $('body').on('touchstart.dropdown', function (e) { 
			      e.stopPropagation(); 
			  });
			  $(document).on('click','.dropdown-menu a',function(){
			      document.location = $(this).attr('href');
			  });
			})
		      }(window.jQuery)
		</script>";
		if(CurrentUser::$admin || CurrentUser::$uploader){
			echo "<script src='src/js/admin.js'></script>";
			echo "<script src='src/js/plupload-ui.js'></script>";
		}	
	  }
}
?>