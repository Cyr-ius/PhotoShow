<?
class WS_Script
{
	function __construct(){
		Settings::init();
		CurrentUser::init();
	}
		
	public function list_scripts(){

		$stack = array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',
		'inc/jquery-ui.min.js',
		'inc/bootstrap/js/bootstrap.min.js',
		'inc/bootstrap/js/bootstrap-modalmanager.js',
		'inc/bootstrap/js/bootstrap-modal.js',
		'inc/plupload/js/plupload.full.min.js',
		'inc/plupload/js/jquery.plupload.queue/jquery.plupload.queue.min.js',
		'inc/jquery.ui.touch-punch.min.js',
		'inc/jquery.mousewheel.js',
		'inc/history.min.js',
		'inc/jquery.fullscreen.js',
		'inc/jquery.scrollstop.js',
		'inc/jquery.scrollTo.js',		
		'inc/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',		
		'inc/messenger/js/messenger.min.js',		
		'inc/messenger/js/messenger-theme-future.js',	
		'inc/jquery.lazyload.min.js',
		'src/js/menu.js',		
		'src/js/image_panel.js',
		'src/js/slideshow.js',
		'src/js/keyboard.js',
		'src/js/panel.js');

		if(CurrentUser::$admin || CurrentUser::$uploader){
			array_push($stack,'src/js/admin.js','src/js/plupload-ui.js');
		}

		return $stack;

	} 


}
?>







	

