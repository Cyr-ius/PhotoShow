/**
 * This file implements admin.
 * 
 * Javascript
 *
 * LICENSE:
 * 
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package	  PhotoShow
 * @category  Website
 * @author	  Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license	  http://www.gnu.org/licenses/
 * @link	  http://github.com/thibaud-rohmer/PhotoShow
 */

function init_admin(){
	
	$(".menu_title").draggable({
		cursor: 		"move",
		zIndex: 		1050,
		helper: 		'clone',
		appendTo: 	'body',
		scroll: 		false,
		revert: 		true
	});
	
	$( ".menu_title,.directory" ).droppable({
		greedy: true
	});

	$(".directory").draggable({
		cursor: 		"move",
		zIndex: 		1050,
		opacity: 		0.5,
		helper: 		'clone',
		appendTo: 	'body',
		scroll: 		false,
		revert: 		true
	});

	$(".item").draggable({
		cursor: 		"move",
		zIndex: 		1050,
		helper: 		'clone',
		cursorAt: 	{left:50,top:64},
		opacity: 		0.5,
		appendTo: 	'body',
		scroll: 		false,
		revert: 		true
	});
	
	$(".accountitem").draggable({
		cursor: 		"move",
		zIndex: 		1050,
		helper: 		'clone',
		cursorAt: 		{left:25,top:25},
		appendTo: 		'body',
		scroll: 		false,
		revert: 		true
	});	
	
	$(".bin,.submenu,.directory").droppable({
		hoverClass: "hovered",
		drop: 	function(event, ui){
						var dragg = ui.draggable;
						obj =  ui.draggable;
						dragg.draggable('option','revert',false);
						from  =  dragg.children('span.path').text();
						to 	  = $(this).children("span.path").text();
						if (to=="bin") { var method="WS_MgmtFF.delete";}else{ var method="WS_MgmtFF.move";}
						if($(dragg).hasClass("item")){
							if($(dragg).hasClass("selected")){
								$(".panel .item.selected").each(function(){
									from = $(this).children(".path").text();
									var obj = $(this);
									var js = JSON.stringify({"jsonrpc":"2.0","method":method,"params":[from,to],"id":"1"});
									$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
									.done(function(data){
										if (!data.error) {
											obj.parent().parent().remove();
										} else {
											get_message(1,data.error.data.fullMessage);
										}
									});										
								});
							} else {
								var js = JSON.stringify({"jsonrpc":"2.0","method":method,"params":[from,to],"id":"1"});
								$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
								.done(function(data){
									if (!data.error) {
										$('.thumbs').masonry('remove',obj).masonry('layout');
									} else {
										get_message(1,data.error.data.fullMessage);
									}
								});
							}
						} else {
							var js = JSON.stringify({"jsonrpc":"2.0","method":method,"params":[from,to],"id":"1"});
							$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
							.done(function(data){
								if (!data.error) {
									$('.menu span:contains('+from+')').parent().remove();
									$('.albums span:contains('+from+')').parent().remove();
									$('.thumbs').masonry('layout');
									$(".menu").load(".?j=Men&f="+encodeURI(currentpath),init_menu);
								} else {
									get_message(1,data.error.data.fullMessage);
								}
							});
						}
				}
	});	

	$(".groupitem").droppable({
		hoverClass:  "hovered",
		drop: 	function(event,ui){
					var dragg = ui.draggable;
					if($(dragg).hasClass("accountitem")){
						dragg.draggable('option','revert',false);
						acc = dragg.children(".name").text();
						group = $(this).children(".name").text();
						var js = JSON.stringify({"jsonrpc":"2.0","method":"WS_Account.add_group","params":[acc,group],"id":"1"});
						$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
						.done(function(data){
							if (!data.error) {
								$(target+' .modal-body').load($(window).attr("url"),init_admin);
							} else {
								get_message(1,data.error.data.fullMessage);
							}
						});						
					}
				}
	})
	
	$("a[data-toggle=modaladmin]").unbind();
	$("a[data-toggle=modaladmin]").click(function() {
		target = $(this).attr('data-target');
		url = $(this).attr('data-href');		 
		$(target+' .modal-body').load(url,init_admin);
	 });	
	
	$("#adminchoiceaccount-form").unbind();
	$("#adminchoiceaccount-form").change(function(){
		$form = $(this);
		var js = JSON.stringify({"jsonrpc":"2.0","method":"WS_Account.get","params":$form.serializeObject(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				populateForm("#adminaccount-form", JSON.parse(JSON.stringify(data.result)));
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});			
	return false;	
	});	

	//Button RecycleBin (Menubar)
	$("#bin").unbind();
	$("#bin").click(function(){
		$(".panel .item.selected").each(function(){
			file = $(this).children(".path").text();
			var obj = $(this);
			var js = JSON.stringify({"jsonrpc":"2.0","method":"WS_MgmtFF.delete","params":[file],"id":"1"});
			$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
			.done(function(data){
				if (!data.error) {
					obj.parent().parent().remove();
				} else {
					get_message(1,data.error.data.fullMessage);
				}
			});
		});
	return false;
	});	
	
	// Button Clean thumbnails (Menubar)
	$("#button_thb").unbind();
	$("#button_thb").click(function(){
		$.post('?t=Adm&a=DAl',{'cleanpath': $('span.currentpath').text()},function(data,info){
			get_message(data.result,data.desc);
		});
	});		
	
	//Reload Modal-body
 	$('#gthumb-form,#delcomment-form,#createtoken-form,#deltoken-form,#delacc-form,#rmgroup-form,#rmacc-form,#creategroup-form,#delgroup-form,#adminregister-form,#adminaccount-form').unbind();
	$('#gthumb-form,#delcomment-form,#createtoken-form,#deltoken-form,#delacc-form,#rmgroup-form,#rmacc-form,#creategroup-form,#delgroup-form,#adminregister-form,#adminaccount-form').submit(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":$(this).serializeObject(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				$(target+' .modal-body').load($(window).attr("url"),init_actions);
				get_message(0,"Action successful");
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
	return false;	
	});	
	
	//Reload Modal-body (not Submit button)
	$('#admintype-form').unbind();
	$('#admintype-form').click(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":$(this).serializeObject(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				$(target+' .modal-body').load($(window).attr("url"),init_actions);
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});		
	return false;	
	});	
	
	//Reload and Close Modal
 	$('#createfolder-form').unbind();
	$('#createfolder-form').submit(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":$(this).serializeObject(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				$.get('?j=Album&f='+data.result.path,function(data){
					var $boxes = $(data);
					$('.albums .thumbs').append($boxes).masonry( 'appended', $boxes ).parent().show(init);
					$(".menu").load(".?j=Men&f="+encodeURI(currentpath),init_menu);
				});
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
		$(target).modal('hide');
	return false;	
	});	
	
 	$('#renamefolder-form').unbind();
	$('#renamefolder-form').submit(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":$(this).serializeObject(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				update_url("?f="+data.result.path,'new_path');
				$(".panel").load(".?j=Pan&f="+data.result.path,init);
				$(".menu").load(".?j=Men&f="+data.result.path,init_menu);				
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
		$(target).modal('hide');
	return false;	
	});	
	
	
	//Json with ModalAdmin page
	$('#adminrights-form').unbind();
	$('#adminrights-form').change(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params": $(this).serializeObject(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				get_message(0,"Save successful");
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
	return false;	
	});	
	
	//Json with ModalAdmin page
	$('#setting-form').unbind();
	$('#setting-form').change(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params": $(this).serializeArray(),"id":"1"});
		$.ajax({	url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				get_message(0,"Save successful");
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
	return false;	
	});


}



function init_infos(){
	$('#button_createdir').attr('data-href',$(location).attr('search')+"&t=MkD");
	$('#button_renamedir').attr('data-href',$(location).attr('search')+"&t=MvD");
	$('#button_token').attr('data-href',$(location).attr('search')+"&t=Token");		
	$('#button_rights').attr('data-href',$(location).attr('search')+"&t=Rights");
	$('#button_download').attr('href',$(location).attr('search')+"&t=Zip");
	$('#button_comm').attr('data-href',$(location).attr('search')+"&t=Com");
}

function init_textinfo(){
	
	$("#edit_textinfo,#editti-form,#delti-form").unbind();
	$("#edit_textinfo").click(function(){
		if ( $('.textinfoadmin').is(':visible')){
			$('.textinfo').show("slide",{direction:"down"},600);
			$('.textinfoadmin').hide("slide",{direction:"up"},600);
		}else{
			$('.textinfo').hide("slide",{direction:"down"},600);			
			$('.textinfoadmin').show("slide",{direction:"up"},600);
		}
	});
	
	$("#editti-form,#delti-form").submit(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":$(this).serializeObject(),"id":"1"});
		$.ajax({url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				$('.panel').load($(location).attr('search')+"&j=Pan",init);
				$('.textinfo').show("slide",{direction:"down"},600);
				$('.textinfoadmin').hide("slide",{direction:"up"},600);
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});		
	return false;	
	});		
	
}

function init_list(){
	if ($('.view_list').size()==1) { return;}
	$('#view-list').addClass('active');
	$('#view-thumb').removeClass('active');
	var tr = '<thead><tr><th>Preview</th><th>Name</th><th>Path</th><th><input class=\'select_all\' type=\'checkbox\'/></th></tr></thead>';
	$('.images .thumbs').children('li').each(function(){
		tr = tr+'<tr><td style=\'height: 120px; width: 120px;\'><li class=\'item \'>'+$(this).html()+'</li></td><td>'+$(this).children('.name').text()+'</td><td>'+$(this).children('.path').text()+'</td><td><input class=\'item_select\' type=\'checkbox\'/></td></tr>';
	});
	$('.videos .thumbs').children('li').each(function(){
		tr = tr+'<tr ><td style=\'height: 120px; width: 120px;\'><li class=\'item \'>'+$(this).html()+'</li></td><td>'+$(this).children('.name').text()+'</td><td>'+$(this).children('.path').text()+'</td><td><input class=\'item_select\' type=\'checkbox\'/></td></tr>';
	});	
	
	view_grid = $('.boardlines').html();
	$('.boardlines').html('<table class=\'table table-striped well view_list\'>'+tr+'</table>');
	$('.boardlines .lazy').attr('width','');
	$('.boardlines .lazy').attr('max-width','120px');
	$('.boardlines .lazy').attr('height','');		
	$('.boardlines .lazy').attr('max-height','120px');

	
	$(".boardlines .item_select").unbind();
	$(".boardlines .item_select").change(function(){
		if ($(this).is(':checked')) {
		$(this).parent().parent().children().children('.item').addClass("selected");
		} else {
		$(this).parent().parent().children().children('.item').removeClass("selected");
		}
	});
	
	$(".boardlines .select_all").unbind();
	$(".boardlines .select_all").change(function(){
		items = $(this).parent().parent().parent().parent().find('.item');
		if ($(this).is(':checked')) {
			$(".boardlines input:checkbox").attr('checked',true);
			$(".boardlines .item").addClass('selected');			
		} else {
			$(".boardlines input:checkbox").attr('checked',false);
			$(".boardlines .item").removeClass('selected');			
		}
	});
	$('.boardlines').tooltip({selector: "a[rel=tooltip]"});
}
