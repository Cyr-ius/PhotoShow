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
	
	$( ".menu_title,.directory" ).droppable({ greedy: true });

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

	$(".submenu,.directory").droppable({
		hoverClass: "hovered",
		drop: 		function(event, ui){
						
						var dragg = ui.draggable;
						dragg.draggable('option','revert',false);
						from  =  dragg.children('span.path').text();
						to 	  = $(this).children("span.path").text();
						if($(dragg).hasClass("selected")){
							$(".panel .item.selected").each(function(){
								from = $(this).children(".path").text();
								$(".panel").load(".?t=Adm&a=Mov&j=Pan",{'pathFrom' : from,'pathTo' : to, 'move':'directory'},init_panel);
							});
						}else{ 
							if($(dragg).hasClass("item")){
								$(".panel").load(".?t=Adm&a=Mov&j=Pan",{'pathFrom' : from,'pathTo' : to, 'move':'directory'},init_panel);	
							}else{
								$("#content").load(".?t=Adm&a=Mov&j=Pag",{'pathFrom' : from,'pathTo' : to, 'move':'directory'},function(){
									$('.menu').mCustomScrollbar({scrollButtons:{enable:true}});
									init_panel();
								});								
							}
						}
						
					}
	});

	$(".bin").droppable({
		hoverClass: "hovered",
		drop: 		function(event, ui){
						var dragg = ui.draggable;
						dragg.draggable('option','revert',false);
						file  = dragg.children('span.path').text();
			
					 if($(dragg).hasClass("selected")){
						$(".panel .item.selected").each(function(){
							file = $(this).children(".path").text();
							$(".panel").load("?t=Adm&a=Del&j=Pan",{'del' : file },init_panel);
						});
					}else{ 

						if($(dragg).hasClass("item")){
							$(".panel").load("?t=Adm&a=Del&j=Pan",{'del' : file },init_panel);
						}else{
							$("#content").load("?t=Adm&a=Del&j=Pag",{'del' : file },function(){
								init_panel();
								$('.menu').mCustomScrollbar({scrollButtons:{enable:true}});		
							});
						}
					}

					}
	});

	$(".groupitem").droppable({
		hoverClass: 	"hovered",
		drop: 			function(event,ui){

							var dragg = ui.draggable;
							if($(dragg).hasClass("accountitem")){
								dragg.draggable('option','revert',false);
								acc = dragg.children(".name").text();
								group = $(this).children(".name").text();
								$("#ModalAdmin .modal-body").load("?t=Adm&a=AGA",{'acc' : acc, 'group' : group },init_admin);
							}
						}
	})
	
	$('#Abo,#Sta,#VTk,#Set,#Acc,#EdA').unbind();
	$('#Abo,#Sta,#VTk,#Set,#Acc,#EdA').click(function(){
		$(target+' .modal-body').load($(this).attr('href'),init_admin);
		$(target+' .modal-footer .modal-infos').hide();
		$(target+' .modal-footer .modal-infos').removeClass('alert-info');
	return false;		
	});
	
	$("#adminchoiceaccount-form,#adminaccount-form").unbind();
	$("#adminchoiceaccount-form,#adminaccount-form").submit(function(){
		$.post($(this).attr('action'),$(this).serialize(),function(data){
			$(target+' .modal-body').html(data);
			init_admin();
		});
	return false;	
	});	

	$("#button_thb").unbind();
	$("#button_thb").click(function(){
		$.post('?t=Adm&a=DAl&j=JSon',{'cleanpath': currentpath},function(data){
			alert(data.desc);
		});
	return false;	
	});		

	//Json with reload page
	$('#createfolder-form,#renamefolder-form,#logins-form').unbind();
	$('#createfolder-form,#renamefolder-form,#logins-form').submit(function(){
		$('#myModal .modal-footer .modal-infos').hide();
		$('#myModal .modal-footer .modal-infos').removeClass('alert-info');
		$.post($(this).attr('action')+'&j=JSon',$(this).serialize(),function(data){
			if (data.result ==0) {
				$('#myModal .modal-footer .modal-infos').removeClass('alert-error').addClass('alert-info');
				url = data.url+data.js;
				window.location.replace(data.url);
			}
			$('#myModal .modal-footer .modal-infos').text(data.desc).show();
		});
	return false;	
	});
	
	//Json with myModal page
	$('#admintype-form,#adminrights-form,#admintokens-form,#account-form,#register-form,#comments-form,#delcomment-form').unbind();
	$('#admintype-form,#adminrights-form,#admintokens-form,#account-form,#register-form,#comments-form,#delcomment-form').submit(function(){
		$('#myModal .modal-footer .modal-infos').hide();
		$('#myModal .modal-footer .modal-infos').removeClass('alert-info');
		url = $(this).attr('action');
		$.post($(this).attr('action')+'&j=JSon',$(this).serialize(),function(data){
			if (data.result ==0) {
				$('#myModal .modal-footer .modal-infos').removeClass('alert-error').addClass('alert-info');
				$.get(url,function(data){
					$('#myModal .modal-body').html(data); 
					init_admin();
				});
			}
			$('#myModal .modal-footer .modal-infos').text(data.desc).show();
		});
	return false;	
	});		
	
	//Json with ModalAdmin page
	$('#deltoken,#setting-form,#gthumb-form,#delthumb-form,#adminregister-form,.addgroup,.removegroup,.removeacc,#rmacc-form,#rmgroup-form').unbind();
	$('#deltoken,#setting-form,#gthumb-form,#delthumb-form,#adminregister-form,.addgroup,.removegroup,.removeacc,#rmacc-form,#rmgroup-form').submit(function(){
		$('#ModalAdmin .modal-footer .modal-infos').hide();
		$('#ModalAdmin .modal-footer .modal-infos').removeClass('alert-info');
		url = $(this).attr('action');
		$.post($(this).attr('action')+'&j=JSon',$(this).serialize(),function(data){
			if (data.result ==0) {
				$('#ModalAdmin .modal-footer .modal-infos').removeClass('alert-error').addClass('alert-info');
				$.get(url,function(data){
					$('#ModalAdmin .modal-body').html(data); 
					init_admin();
				});
			}
			$('#ModalAdmin .modal-footer .modal-infos').text(data.desc).show();
		});
	return false;	
	});	
}

function init_infos(){
	$('#button_createdir').attr('data-href',$(location).attr('search')+"&j=MkD");
	$('#button_renamedir').attr('data-href',$(location).attr('search')+"&j=MvD");
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
		$.post($(this).attr('action')+'&j=Pan',$(this).serialize(),function(data){
			$('.panel').html(data);
			$('.textinfo').show("slide",{direction:"down"},600);
			$('.textinfoadmin').hide("slide",{direction:"up"},600);
			init_panel();
		});
	return false;	
	});		
	
}

function init_list(){

	$('#view-list').addClass('active');
	$('#view-thumb').removeClass('active');
	var tr = '<thead><tr><th>Preview</th><th>Name</th><th>Path</th><th><input class=\'select_all\' type=\'checkbox\'/></th></tr></thead>';
	$('.images .thumbnails').children('li').each(function(){
		tr = tr+'<tr><td style=\'height: 120px; width: 120px;\'><li class=\'item \'>'+$(this).html()+'</li></td><td>'+$(this).children('.name').text()+'</td><td>'+$(this).children('.path').text()+'</td><td><input class=\'item_select\' type=\'checkbox\'/></td></tr>';
	});
	$('.videos .thumbnails').children('li').each(function(){
		tr = tr+'<tr ><td style=\'height: 120px; width: 120px;\'><li class=\'item \'>'+$(this).html()+'</li></td><td>'+$(this).children('.name').text()+'</td><td>'+$(this).children('.path').text()+'</td><td><input class=\'item_select\' type=\'checkbox\'/></td></tr>';
	});	
	old_view = $('.boardlines').html();
	$('.boardlines').html('<table class=\'table table-striped well\'>'+tr+'</table>');
	$('.boardlines .lazy').attr('width','');
	$('.boardlines .lazy').attr('max-width','120px');
	$('.boardlines .lazy').attr('height','');		
	$('.boardlines .lazy').attr('max-height','120px');

	
	$(".boardlines .item_select").unbind();
	$(".boardlines .item_select").change(function(){
		$(this).parent().parent().children().children('.item').addClass("selected");
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
}