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
var exifvisible = 0;
var viewlist = 0;
var lazyload = 0;
 
 function init_panel(){
	$("#button_createdir,#edit_textinfo").show();
	$('#button_exif,#button_downloadorig,#button_vieworig,#slideshow,#timeshow').hide();
	if (viewlist==1){init_list();}
 	currentpath = $('span.currentpath').text();	 
	
	$(".panel .item a").unbind();
	$(".panel .item a").click(function(){
		url = $(this).attr("href");
		update_url(url);
		$(".image_panel").load(url+"&j=Pan",function() {
			if (exifvisible==1) {
				$('.exif').load(url+"&t=Exif",function() {
					$('.exif').show();
				});
			}	
			$(".item").clone().appendTo(".linear_panel .thumbnails").removeAttr('style');
			$(".panel").hide("slide",{direction:"down"},600);
			$(".bigpanel").show("slide",{direction:"up"},600,init);
		});		
		return false;
	});

	$('.images .thumbs').masonry({columnWidth:10,gutter:10, itemSelector: '.item'});
	$('.videos .thumbs').masonry({columnWidth:10,gutter:10, itemSelector: '.item'});
	$('.albums .thumbs').masonry({columnWidth:10,gutter:10, itemSelector: '.directory'});
	if ($('.moxie-shim-html5').length==0) {init_plupload();}
	if (lazyload==0) {
		$('img.lazy').lazyload({effect : "fadeIn",container:$(".panel"),threshold : 200}); 
		lazyload=1;
	} else {
		$('img.lazy').lazyload('update');
	}
	$(".linear_panel .thumbnails").children().remove();	
}

function init_hiders(){
	
	if ($('#welcome').is(':visible')){$('.menu , #menubar').hide()}	
	
	$("a[data-toggle=modal]").unbind();
	$("a[data-toggle=modal]").click(function() {
		target = $(this).attr('data-target');
		title = $(this).attr('data-title');
		url = $(this).attr('data-href');		 
		$(target+"Label").text(title);
		$(target+' .modal-body').load(url,init_actions);
	 });
	
	$(".directory").unbind();
	$(".directory").hover(function(e){
		var i = $(this).children(".alt_dir_img");
		var x = Math.floor((Math.random()*i.length)+1)-1;
		var img = $(i[x]).text();
		e = $(this);
		if(e.children(".img_bg").text() != img){
			e.children(".img_bg").text(img);
			$(e).css("background-image","url(\"?t=Thb&f="+img+"\")");

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
	
	// On clicking img
	$("#button_vieworig").unbind();		
	$("#button_vieworig").click(function(){
		window.open($(location).attr('search')+"&t=Big");
	return false;
	});	
	
	// On clicking get
	$("#button_downloadorig").unbind();		
	$("#button_downloadorig").click(function(){
		window.location=$(location).attr('search')+"&t=BDl";
	return false;
	});			
	
	$("#view-list").unbind();
	$("#view-list").click(function(){
		viewlist = 1;
		//Load init
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
		//Load init
		init();
	return false;	
	});		

	$("#menu_hide").unbind();
	$("#menu_hide").click(function(){
		if ($('.menu').is(':visible')){
			$('.panel,.bigpanel').animate({'width':'100%'},600);
			$('.menu').hide("slide",{direction:"left"},600,function() {
				$('#menu_hide i').removeClass('icon-backward').addClass('icon-forward');
				$('.thumbs').masonry({"columnWidth": 10,"gutter":10,"itemSelector": ".item"});
			});
		} else {
			$('.panel,.bigpanel').animate({'width':'84%'},600,function(){
				$('.panel,.bigpanel').css('width','');
			});
			$('.menu').show("slide",{direction:"left"},600,function(){
				$('#menu_hide i').removeClass('icon-forward').addClass('icon-backward');
				$('.thumbs').masonry({"columnWidth": 10,"gutter":10,"itemSelector": ".item"});
			});

		}
	return false;	
	});
		
	$('#logout').unbind();
	$('#logout').click(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":"WS_Account.logout","params":[],"id":"1"});
		$.ajax({url:'',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				window.location.replace('/');
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});		
	return false;	
	});

}
function init_actions() {
	
	$('#logins-form,#register-form').unbind();
	$('#logins-form,#register-form').submit(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":[$(this).toObject()],"id":"1"});
		$.ajax({url:'',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				window.location.replace($(location).attr('search'));
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
	return false;
	}); 
	
	$('#account-form,#comments-form').unbind();
	$('#account-form,#comments-form').submit(function(){
		var js = JSON.stringify({"jsonrpc":"2.0","method":$(this).attr('action'),"params":[$(this).toObject()],"id":"1"});
		$.ajax({url:'',data:js,type:'POST',dataType:"json",contentType: "application/json"})
		.done(function(data){
			if (!data.error) {
				$(target).modal('hide',get_message(0,"Action sucessfully"));
			} else {
				get_message(1,data.error.data.fullMessage);
			}
		});
	return false;	
	});
	//Load admin
	init_admin();
}

function scrollbar(class_pane,h_bool){
	var e_pane = class_pane;
	$(class_pane).mCustomScrollbar({
	horizontalScroll:h_bool,autoHideScrollbar:false,scrollInertia:0,autoDraggerLength:true,mouseWheel:true,mouseWheelPixels:"auto",
	scrollButtons:{enable: true, scrollSpeed: "auto"},
	advanced:{autoExpandHorizontalScroll:h_bool,updateOnBrowserResize: true,updateOnContentResize:true,autoScrollOnFocus:true},
	contentTouchScroll:true,
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
	scrollbar('.linear_panel',true);
	//Load init
	init();
	if ($(".menu .selected:last").length > 0) $(".menu").scrollTo($(".menu .selected:last"));
	
	//~ $(window).resize(function() {
		//~ $('#video').css('max-width',$('#c_video').width());
	//~ });
	
	
});

/****************** Function Jquery ***************/

$.fn.heightauto = function () {
   this.css("height", ($(window).height()-140-50-72)  + "px");
   return this;
}

$.fn.fillForm = function (data) {
	var frm = this;
	$.each(data, function(key, value){  
		var $ctrl = $('[name='+key+']', frm);  
		switch($ctrl.attr("type"))  {  
			case "text" : case "hidden":  
				$ctrl.val(value);   
				break;   
			case "radio" : case "checkbox":   
				$ctrl.each(function(){
					if($(this).attr('value') == value) {  $(this).attr("checked",value); } 
				});   
				break;  
			default:
				$ctrl.val(value); 
		}  
	});  
	return true;
}