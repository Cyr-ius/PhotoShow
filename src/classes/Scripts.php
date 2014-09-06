<?php 
class Scripts implements HTMLObject
{

	public function __construct() {
	}
	
	public function toHTML(){

		echo "
		<!-- Le javascript
		================================================== -->
		
		<!-- Framwork JQUERY 1.x -->
		<script src='inc/jquery-1.11.0.min.js'></script>
		<script src='inc/jquery-ui.min.js'></script>
		
		<!-- JQUERY Mobile (option Touch) -->
		<script src='inc/jquery.mobile.custom.min.js'></script>				
		
		<!-- Touch-Punch -->
		<script src='inc/jquery.ui.touch-punch.min.js'></script>		
				
		<!-- Framework BootStrap 2.3.2 -->
		<script src='inc/bootstrap/js/bootstrap.min.js'></script>
		<script src='inc/bootstrap/js/bootstrap-modalmanager.js'></script>
		<script src='inc/bootstrap/js/bootstrap-modal.js'></script>
		
		<!-- JQuery plugin : PLUpload -->
		<script src='inc/plupload/js/plupload.full.min.js'></script>
		<script src='inc/plupload/js/jquery.plupload.queue/jquery.plupload.queue.min.js'></script>
		
		<!-- MouseWhell -->
		<script src='inc/jquery.mousewheel.js'></script>
		
		<!-- Mansory  -->
		<script src='inc/jquery.masonry.pkgd.min.js'></script>

		<!-- Form2js  -->
		<script src='inc/form2js/form2js.js'></script>
		<script src='inc/form2js/js2form.js'></script>
		<script src='inc/form2js/jquery.toObject.js'></script>

		<! Video-js -->
		<script src='inc/video-js/video.js'></script>
		<script>videojs.options.flash.swf = 'video-js.swf';</script>

		<!-- History (BUG IE pushstate) -->
		<script src='inc/history.min.js'></script>
		
		<!-- JQuery plugin : API Fullscreen for slideshow -->
		<script src='inc/jquery.fullscreen.js'></script>

		<!-- JQuery plugin : ScrollStop  -->
		<script src='inc/jquery.scrollstop.js'></script>
		
		<!-- JQuery plugin : ScrollStop  -->
		<script src='inc/jquery.scrollTo.js'></script>		
		
		<!-- JQuery plugin : mCustomScrollbar  -->
		<script src='inc/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js'></script>		
		
		<!-- JQuery plugin : Messenger  -->
		<script src='inc/messenger/js/messenger.min.js'></script>		
		<script src='inc/messenger/js/messenger-theme-future.js'></script>		
		
		<!-- Custo -->
		<script src='src/js/panel.js'></script>
		<script src='src/js/menu.js'></script>		
		<script src='src/js/image_panel.js'></script>
		<script src='src/js/slideshow.js'></script>
		<script src='src/js/keyboard.js'></script>
		<script src='inc/jquery.lazyload.min.js'></script>			
		<script id='js_fix'>
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
		echo "
		<script src='src/js/admin.js'></script>
		<script src='src/js/plupload-ui.js'></script>";
		}			    
	  }
}
?>
