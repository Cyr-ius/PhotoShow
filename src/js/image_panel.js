/**
 * This file implements image_panel.
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

/**
 * Initialise the image panel
 */
function init_image_panel(){
	
	$('#button_exif,#button_downloadorig,#button_vieworig,#slideshow,#timeshow').css( "display", "block");
	$("#button_createdir,#edit_textinfo").hide();
	
	//If we are in a view mode were there is a linear panel and no image selected in that panel
	if ($('#linear_panel').length == 1 && $('#linear_panel .selected').length == 0 && $("#linear_panel").is(":visible")){
		if ($('#bigimage #current').size()!=0) {
			url = $('#bigimage #current').css('background-image').replace(/^url|[\(\)\"]/g, ''); 
		}
		if ($('.bigvideo').size()!=0) {
			url = $('#bigvideo').find('source').attr('src'); 
		}
		url = url.slice(url.indexOf('f='));
		$('#linear_panel a[href$="' + url + '"]').parent().addClass("active selected");
	}
	$('.linear_panel').mCustomScrollbar("scrollTo",".thumbnails li.selected");
	$('img.lazy').lazyload('update');
	init_image_bar();
 }	
 
 /**
 * Initialise the image bar
 */
function init_image_bar(){
	
	$('.content_panel').hover(function(){
		$('#next,#prev').show();
	},function(){
		$('#next,#prev').hide()
	});

	// On clicking the bigimage
	$(".content_panel #bigimage").unbind();
	$(".content_panel #bigimage").click(function(){
		if(slideshow_status == 1){
			stop_slideshow();
		} 
		// Edit layout
		$('img.lazy').lazyload().unbind();
		$(".bigpanel").hide("slide",{direction:"up"},600);
		$(".panel").show("slide",{direction:"down"},600,function(){	
			update_url($(".menu .selected:last a").attr("href"),$(".menu .selected:last a").text());
			init();
		});
		
	return false;
	});

	// On clicking an item
	$(".linear_panel .item a").unbind();	
	$(".linear_panel .item a").click(function(){
		url = $(this).attr("href");
		update_url(url,"Image"); 
		$('#current').css('background-image','url("'+url+'&t=Img")');
			if (exifvisible==1) {
				$('.exif').load(url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
	return false;
	});

	// On clicking NEXT
	$(".content_panel #next").unbind();		
	$(".content_panel #next").click(function(){
		var curr_select = $(".linear_panel .selected");
		var new_select 	= curr_select.next('.item');

		if(! new_select.length){
			new_select = curr_select.parent().next().children(".item").first();
		}
		
		if(! new_select.length){
			new_select = $(".linear_panel .item").last();
		}

		new_url = new_select.children("a").attr("href");
		update_url(new_url,"Image");
		 $('<img />').attr('src',new_url+'&t=Img').appendTo('body').css('display','none').load(function(){
				$(this).remove();
			if (exifvisible==1) {
				$('.exif').load(new_url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
			$('.linear_panel').mCustomScrollbar("scrollTo",".thumbnails li.selected");
			$w = $('#current').width();
			$('#next-1').css({"left":$w+'px',"right":'-'+$w+'px','background-image':'url("'+new_url+'&t=Img")'});
			$('#current').hide("slide",{direction:"left"},300);
			$('#next-1').stop().animate({'left':0,'right':0},300,function(){
				 $('#current').remove();
				$('#next-1').attr('id','current');
				$('#bigimage').append('<div id="next-1"></div>');
			});
		});

	
	return false;
	});

	// On clicking PREV
	$(".content_panel #prev").unbind();		
	$(".content_panel #prev").click(function(){
		var curr_select = $(".linear_panel .selected");
		var new_select 	= curr_select.prev();
		
		if(! new_select.length){
			new_select = curr_select.parent().prev().children(".item").last();
		}
		
		if(! new_select.length){
			new_select = $(".linear_panel .item").first();
		}
		
		new_url = new_select.children("a").attr("href");
		update_url(new_url,"Image");

		 $('<img />').attr('src',new_url+'&t=Img').appendTo('body').css('display','none').load(function(){
			$(this).remove();
			if (exifvisible==1) {
				$('.exif').load(new_url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
			$('.linear_panel').mCustomScrollbar("scrollTo",".thumbnails li.selected");
			$w = $('#current').width();
			$('#next-1').css({"left":'-'+$w+'px',"right":$w+'px','background-image':'url("'+new_url+'&t=Img")'});
			$('#current').hide("slide",{direction:"right"},300);
			$('#next-1').stop().animate({'left':0,'right':0},300,function(){
				 $('#current').remove();
				$('#next-1').attr('id','current');
				$('#bigimage').append('<div id="next-1"></div>');
			});
		});


	return false;
	});
	init_slideshow_panel();	
}