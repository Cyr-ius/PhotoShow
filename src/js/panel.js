/**
 * This file implements the JS for panels.
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
 function init_panel(){
	$(".panel,#button_createdir,#button_download,#button_token,#edit_textinfo").show();
	$(".linear_panel .thumbnails").children().remove();
	$('.linear_panel,.image_panel,#button_exif').hide();
	if (viewlist==1){init_list();}
 	currentpath = $('span.currentpath').text();	
	 
	$(".panel .item a").unbind();
	$(".panel .item a").click(function(){

		// Select item
		$(".panel .selected").removeClass("selected");
		$(this).parent().addClass("selected");
		url = $(this).attr("href");
		update_url(url);
		
		// Load image
		$(".image_panel").load(url+"&j=Pan",function() {
			if (exifvisible==1) {
				$('.exif').load(url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
			$(".image_panel").slideDown("fast",function(){
				$(".item").clone().appendTo(".linear_panel .thumbnails");
				$('.linear_panel,#button_exif').show();
				$(".panel,#button_createdir,#button_download,#button_token,#edit_textinfo").hide();
				init_image_panel();
			});
		});		
		return false;
	});
	
	$("a[data-toggle=modal]").unbind();
	$("a[data-toggle=modal]").click(function() {
		target = $(this).attr('data-target');
		title = $(this).attr('data-title');
		url = $(this).attr('data-href');		 
		$(target+"Label").text(title);
		$(target+' .modal-body').load(url,function(){
			init_hiders();
			init_admin();
		});
		$(target+' .modal-footer .modal-infos').hide();
		$(target+' .modal-footer .modal-infos').removeClass('alert-info');
	 });
	
	$(".dir_img").unbind();
	$(".dir_img").mouseover(function(e){
		var i = $(this).children(".alt_dir_img");
		//var x = Math.floor(i.length * Math.random());
		var x = Math.floor(i.length * (e.pageX - $(this).offset().left) / $(this).width());
		var img = $(i[x]).text();
		e = $(this);
		if(e.children(".img_bg").text() != img){
			e.children(".img_bg").text(img);
			$.get("?t=Thb&f="+img,function(){
				$(e).css("background-image","url(\"?t=Thb&f="+img+"\")");
				//$(e).children().children().attr('src','?t=Thb&f='+img);		
			});
		}
		return false;
	});
		
	init_hiders();
	init_admin();
	if ($('.plupload').length ==0) {init_plupload();}
	init_menu();
	
	$('img.lazy').lazyload({event:'scrollstop',effect : "fadeIn",container: $(".center"),threshold : 200});
	$(".menu").scrollTo($(".menu .selected:last"));
}

function init_hiders(){
	
	$('#menu-actions').unbind();
	$('#menu-actions').click(function(){
		init_infos();
		init_textinfo();
	})
	
	$("#button_info").unbind();
	$("#button_info").click(function(){
		if ( $('#comments').is(':visible')){
			$('#comments').hide("slide",{direction:"up"},600);
		}else{		
			$('#comments').show("slide",{direction:"up"},600,init_panel);
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
			init_panel();
	});	
	
	$("#view-thumb").unbind();
	$("#view-thumb").click(function(){
			viewlist = 0;
			$('.boardlines').html(old_view);
			init_panel();
	});		
	
	

	$("#menu_hide").unbind();
	$("#menu_hide").click(function(){
		if ($('.menu').is(':visible')){
			$('.menu').hide("slide",{direction:"left"},600,function() {
				$('.center').removeClass('span10').addClass('span12');
				$('.center').css('padding-left','20px');
				});
		} else {
			$('.menu').show("slide",{direction:"left"},600,function(){
				$('.center').removeClass('span12').addClass('span10');
				$('.center').css('padding-left','');
				});
		}
	});
		
	$('#logout').unbind();
	$('#logout').click(function(){
		$.get($(location).attr('pathname')+'?t=Log&j=JS',function(){
		window.location.replace('/');	
		});
	return false;	
	});
		
}

//~ function preload() {
	
	//~ $("span.pathd").each(function()	{
	    //~ var element = $(this);
	    
	    //~ // Store the original src
	    //~ var originalSrc = '?'+element.text();
	    
		//~ // Load the original image
		//~ $('<img />').attr('src', originalSrc).load(function(){
			//~ // Image is loaded, replace the spinner with the original
			//~ $(element.parent()).children('a.thumbnail').children().attr('src',originalSrc);
		//~ }).filter(function() { return this.complete; }).load();
	//~ });
	
//~ }



$("document").ready(function(){
	exifvisible = 0;
	viewlist = 0;
	init_panel();
	$('.menu').mCustomScrollbar({scrollButtons:{enable:true}});
});