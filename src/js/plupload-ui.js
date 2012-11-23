function init_plupload() {

	var uploader = new plupload.Uploader({
	        runtimes : 'html5,html4,flash,silverlight',
	        url : '?t=Adm&a=Upl&j=JSon',
		container : 'uploader',
		browse_button : 'dropzone',
		drop_element : 'dropzone',
		multipart_params : {path: currentpath},
	        max_file_size : '50mb',
		unique_names : true,		
		flash_swf_url : '/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '/plupload/js/plupload.silverlight.xap',
	        // Resize images on clientside if we can
	        resize : {width : 1920, height : 1200, quality : 90},
	 
	        // Specify what files to browse for
		filters : [
			{title : "Image files", extensions : "jpg,gif,png,jpeg,tiff"},
			{title : "Zip files", extensions : "zip"},
			{title : "Video files", extensions : "flv,mov,mpg,mpeg,mp4,ogv,mts,3gp,webm,avi,wmv"}
		]
	});
	
	uploader.destroy();
	uploader.bind('Init', function(up, params) {
		//$('#filelist').html("<div class='info'>Current runtime: " + params.runtime + "</div>");
	});

	$('#button_upload').unbind();
	$('#button_upload').click(function(e) {
		if($('#rights_upload').find('.active').val()){
			uploader.settings.multipart_params.inherit = $('#rights_upload').find('.active').val();
		}
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		if ($('.uploadbtn').is(':hidden')) {
			$('.uploadbtn').show();
		}
		$.each(files, function(i, file) {
			$('#files').append('<tr  id="' + file.id + '"><td  class="name">' + file.name + '</td><td class="span6"><div class="progress progress-success progress-striped active" aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar"><div class="bar" style="width:0%;"></div></div></td><td class="size">' + plupload.formatSize(file.size) +'<td><td><a class="action" href="#"><i class="icon-remove-sign"></i></a></td></tr>');			
			$('#' + file.id + " .action").bind('click',function(){
				uploader.removeFile(file);
				$('#' + file.id).fadeOut(1000);
			})
		});
		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " .bar").css('width',file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file) {
		$('#' + file.id + " .action").html("<i class='icon-ok-sign'></i>");
		$('#' + file.id).delay(4000).fadeOut(1000);
	});
	
	uploader.bind('UploadComplete',function(up,file){
		$.get($(location).attr('search')+'&j=Pan',$(this).serialize(),function(data){
			$('.panel').html(data);
			$('.uploadbtn').hide();
			update_url($(location).attr('search'));	
			init_panel();
		});		
	});
	
	$('#dropzone').droppable({hoverClass: "hovered"});
}