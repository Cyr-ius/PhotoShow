function init_plupload() {

	var uploader = new plupload.Uploader({
	        runtimes : 'html5,html4,flash,silverlight',
	        url : '?t=Adm&a=Upl',
		container : 'uploader',
		browse_button : 'additem',
		drop_element : 'additem',
		multiple_queues : true,
		rename:true,
		multipart_params : {path: $('span.currentpath').text()},
	        max_file_size : '200mb',
		unique_names : true,		
		flash_swf_url : '/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '/plupload/js/plupload.silverlight.xap',
	        // Resize images on clientside if we can
	        resize : {width : 1920, height : 1200, quality : 90},
	        // Specify what files to browse for
		filters : [
			{title : "image", extensions : "jpg,gif,png,jpeg,tiff"},
			{title : "file", extensions : "zip"},
			{title : "video", extensions : "flv,mov,mpg,mpeg,mp4,ogv,mts,3gp,webm,avi,wmv"}
		]
	});

	$('#button_upload').unbind();
	$('#button_upload').click(function(e) {
		if($('#rights_upload').find('.active').val()){
			uploader.settings.multipart_params.inherit = $('#rights_upload').find('.active').val();
		}
		uploader.start();
		e.preventDefault();
	});
	
	uploader.unbindAll();

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		
		if ($('.uploadbtn').is(':hidden')) {
			$('.uploadbtn').show();
		}
		
		$.each(files, function(i, file) {	

			var additem = $('#additem').clone().attr("id",file.id);
			$(additem).children('.thumbnail').children('img').attr('src','../inc/loading_img.gif');
			var $boxes = $(additem);
			$('.images .thumbs').append($boxes).masonry( 'appended', $boxes ).parent().show();
			$('.images .thumbs').masonry('layout');
			$('.images .thumbs').masonry( 'on', 'layoutComplete', function(){
				
				
				
				
							var previewimg = new o.Image();
			previewimg.oid = file.id;

			previewimg.onload = function() {
				previewimg.downsize(120,120,true);
				var src_img=previewimg.getAsDataURL();
				$('#'+this.oid+' img').attr('src',src_img);
				$('#'+this.oid+' .progress').show();
				
				$('#' +this.oid + " a").bind('click',function(){
					uploader.removeFile(file);
					$('#' + this.oid).fadeOut(1000,function(){
						$('.images .thumbs').masonry('layout');	
					});
				})
				previewimg.destroy();
			};			
			 previewimg.load(file.getSource());	
				
				
				
				
				
				
				});
			

		});
		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " .bar").css('width',file.percent + "%");
	});
	
	uploader.bind('Error', function(up, err) {
		get_message(1,err.code+'-'+err.message+(err.file ? "-" + err.file.name : ""));	
		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file,info) {
		//~ $('#' + file.id + " .action").html("<i class='icon-ok-sign'></i>");
		var obj = JSON.parse(info.response);
		if (obj.result){
			if (obj.result.type=='Image') { var type='.images'};
			if (obj.result.type=='Video') { var type='.videos'};
			if (obj.result.type=='File') { var type='.albums'};
			$.get('?j=Item&f='+obj.result.path,function(data){
				var $boxes = $(data);
				$('#'+file.id).replaceWith($boxes);
				$('#' + file.id).fadeOut(100, function(){
						$(type+' .thumbs').append($boxes).masonry( 'appended', $boxes ).parent().fadeIn(100);
						init();
					});
				$('#' + file.id).remove();
			});
			
		}	
	});
	
	uploader.bind('UploadComplete',function(up,file){
		$('.uploadbtn').hide();
		get_message(0,'Upload Completed');
	});
	$('#additem').bind('dragover',function(){
		$('#additem').addClass('hovered');
	});
	$('#additem').bind('dragleave',function(){
		$('#additem').removeClass('hovered');
	});
	$('#additem').droppable({hoverClass: "hovered"});

}