function init_plupload() {

	var uploader = new plupload.Uploader({
	        runtimes : 'html5,html4,flash,silverlight',
	        url : '?t=Adm&a=Upl',
		container : 'uploader',
		browse_button : 'dropzone',
		drop_element : 'dropzone',
		multiple_queues : true,
		multipart_params : {path: $('span.currentpath').text()},
	        max_file_size : '200mb',
		unique_names : true,		
		flash_swf_url : '/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '/plupload/js/plupload.silverlight.xap',
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
	
	uploader.bind('Init',function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":"WS_MgmtFF.pluploadsets","params":[],"id":"1"});
		$.ajax({url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
			.done(function(data){
				uploader.setOption('filters',data.result.filters);
				uploader.setOption('resize',data.result.resize);
			});
	});

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
		get_message(1,err.code+'-'+err.message+(err.file ? "-" + err.file.name : ""));	
		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file,info) {
		$('#' + file.id + " .action").html("<i class='icon-ok-sign'></i>");
		$('#' + file.id).delay(20).fadeOut(20);
		var obj = JSON.parse(info.response);
		if (obj.result){
			if (obj.result.type=='Image') { var type='.images'};
			if (obj.result.type=='Video') { var type='.videos'};
			if (obj.result.type=='File') { var type='.albums'};
			$.get('?j=Item&f='+obj.result.path,function(data){
				var $boxes = $(data);
				$(type+' .thumbs').append($boxes).masonry( 'appended', $boxes ).parent().show(function(){
					$('img.lazy').lazyload('update');
					init();
				});
			});
			
		}	
	});
	
	uploader.bind('UploadComplete',function(up,file){
		$('.uploadbtn').hide();
		get_message(0,'Upload Completed');
	});
	
	$('#dropzone').droppable({hoverClass: "hovered"});
}