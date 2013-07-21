/**
 * This file implements the JS for panels.
 * me
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
 function init_panel(){
	$("#button_createdir,#edit_textinfo").show();
	$('#button_exif').hide();
	if (viewlist==1){init_list();}
 	currentpath = $('span.currentpath').text();	 
	//~ $('img.lazy').lazyload().unbind();
	
	$(".panel .item a").unbind();
	$(".panel .item a").click(function(){

		url = $(this).attr("href");
		update_url(url);
		
		// Load image
		$(".image_panel").load(url+"&j=Pan",function() {
			if (exifvisible==1) {
				$('.exif').load(url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
			$(".linear_panel .thumbnails").children().remove();
			$(".item").clone().appendTo(".linear_panel .thumbnails");	
			$('img.lazy').lazyload().unbind();			
			$(".panel").hide();
			$(".bigpanel").show("slide",{direction:"up"},600,init);
		});		
		return false;
	});

	if ($('.plupload').length ==0) {init_plupload();}
	$('img.lazy').lazyload({effect : "fadeIn",container:$(".panel"),threshold : 200});
}

function init_hiders(){
	
	if ($('#welcome').is(':visible')){$('.menu , .menubar').hide()}	
	
	$("a[data-toggle=modal]").unbind();
	$("a[data-toggle=modal]").click(function() {
		target = $(this).attr('data-target');
		title = $(this).attr('data-title');
		url = $(this).attr('data-href');		 
		$(target+"Label").text(title);
		$(target+' .modal-body').load(url,init_admin);
	 });
	 
	$("a[data-type=account] , a[data-type=register],a[data-type=login]").unbind();
	$("a[data-type=account] , a[data-type=register],a[data-type=login]").click(function() {
		target = $(this).attr('data-target');
		title = $(this).attr('data-title');
		url = $(this).attr('data-href');		 
		$(target+"Label").text(title);
		$(target+' .modal-body').load(url,init_login);
	 });	 
	
	$(".dir_img").unbind();
	$(".dir_img").mouseover(function(e){
		var i = $(this).children(".alt_dir_img");
		var x = Math.floor(i.length * (e.pageX - $(this).offset().left) / $(this).width());
		var img = $(i[x]).text();
		e = $(this);
		if(e.children(".img_bg").text() != img){
			e.children(".img_bg").text(img);
			$.get("?t=Thb&f="+img,function(){
				$(e).css("background-image","url(\"?t=Thb&f="+img+"\")");
			});
		}
	return false;
	});
	
	$('#menu-actions').unbind();
	$('#menu-actions').click(function(){
		init_infos();
	})
	
	$("#button_info").unbind();
	$("#button_info").click(function(){
		if ( $('#comments').is(':visible')){
			$('#comments').hide("slide",{direction:"up"},600);
		}else{		
			$('#comments').show("slide",{direction:"up"},600);
		}		
	});	
	
	
	$("#button_exif").unbind();
	$("#button_exif").click(function(){
		if ($('.exif').is(':visible')){
			$('.exif').hide("slide",{direction:"down"},600);
			exifvisible = 0;
		} else {
			exifvisible = 1;
			$('.exif').load($(location).attr('search')+"&t=Exif",function() {
			$('.exif').show("slide",{direction:"down"},600);			
			});
		}		
	});	

	$("#view-list").unbind();
	$("#view-list").click(function(){
		viewlist = 1;
		init();
	return false;	
	});	
	
	$("#view-thumb").unbind();
	$("#view-thumb").click(function(){
		viewlist = 0;
		$('.boardlines').tooltip('destroy');
		$('#view-thumb').addClass('active');
		$('#view-list').removeClass('active');
		$('.boardlines').html(view_grid);
		init();
	return false;	
	});		

	$("#menu_hide").unbind();
	$("#menu_hide").click(function(){
		if ($('.menu').is(':visible')){
			$('.menu').hide("slide",{direction:"left"},600,function() {
				$('.panel,.bigpanel').removeClass('span10').addClass('span12');
				$('.panel,.bigpanel').css('padding-left','20px');
				$('#menu_hide i').removeClass('icon-backward').addClass('icon-forward');
			});
		} else {
			$('.panel,.bigpanel').removeClass('span12').addClass('span10');
			$('.panel,.bigpanel').css('padding-left','');
			$('.menu').show("slide",{direction:"left"},600);
			$('#menu_hide i').removeClass('icon-forward').addClass('icon-backward');
		}
	return false;	
	});
		
	$('#logout').unbind();
	$('#logout').click(function(){
		$.get($(location).attr('pathname')+'?t=Log&j=JS',function(){
		window.location.replace('/');	
		});
	return false;	
	});

}
function init_login() {
	$('#logins-form').unbind();
	$('#logins-form').submit(function(){
		$.post($(this).attr('action')+'&j=JSon',$(this).serialize(),function(data){
			get_message(data.result,data.desc);
			if (data.result ==0) {
				window.location.replace($(location).attr('search'));
			}
			
		});
	return false;	
	});	
}

function scrollbar(class_pane,h_bool){
	var e_pane = class_pane;
	$(class_pane).mCustomScrollbar({
	horizontalScroll:h_bool,autoHideScrollbar:false,scrollInertia:0,autoDraggerLength:true,
	scrollButtons:{enable: true, scrollSpeed: "auto"},
	advanced:{autoExpandHorizontalScroll:h_bool,updateOnBrowserResize: true,updateOnContentResize:true,autoScrollOnFocus:true},
	callbacks:{onScroll:function(){$(e_pane).trigger("scroll")}},
	theme:"dark-thin"
	});	
}

function get_message($type_mess,$txt_mess) {
	Messenger.options = {extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',theme: 'future'};
	switch ($type_mess) {
		case 0:
		Messenger().post({message: $txt_mess,type:'info'});
		break;
		case 1:
		Messenger().post({message: $txt_mess,type:'error'});
		break;	
		default:
		Messenger().post({message: $txt_mess,type:'warning'});
		break;
	}
}

function init() {
	if ($('.panel').is(':visible')) {
		init_panel();
		init_hiders();
		init_menu();
		init_admin();
		init_textinfo();
	}
	if ($('.bigpanel').is(':visible')) {
		init_image_panel();
		init_hiders();
		init_menu();
		init_admin();
	}


}

$("document").ready(function(){
	exifvisible = 0;
	viewlist = 0;
	init();
	$(".menu").scrollTo($(".menu .selected:last"));
});