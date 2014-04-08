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
	//~ $('img.lazy').lazyload().unbind();
	$('#button_exif').show();
	$("#button_createdir,#edit_textinfo").hide();
	
	//If we are in a view mode were there is a linear panel and no image selected in that panel
	if ($('#linear_panel').length == 1 && $('#linear_panel .selected').length == 0 && $("#linear_panel").is(":visible")){
		if ($('.bigimage').size()!=0) {
			url = $('.bigimage').css('background-image').replace(/^url|[\(\)\"]/g, ''); 
		}
		if ($('.bigvideo').size()!=0) {
			url = $('.bigvideo').find('source').attr('src'); 
		}
		url = url.slice(url.indexOf('f='));
		$('#linear_panel a[href$="' + url + '"]').parent().addClass("active selected");
	}
	
	init_image_bar();
	$('img.lazy').lazyload({effect : "fadeIn",container:$(".linear_panel"),threshold : 200});
	
	// On mousewheelling
	$(".linear_panel").mousewheel(function(event,delta){
		if($(".linear_panel").is(":visible")){
			this.scrollLeft -= delta * 30;
			event.preventDefault();
		}
	});
	
 }	
 
 /**
 * Initialise the image bar
 */
function init_image_bar(){

	$('#spacer').heightauto();

	// On clicking the bigimage
	$(".bigimage a, .image_bar #back").unbind();
	$(".bigimage a, .image_bar #back").click(function(){
		
		if(slideshow_status == 1){
			stop_slideshow();
		} 
		// Edit layout
		$('img.lazy').lazyload().unbind();
		$(".bigpanel").hide();
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
		$('.image_panel').load(url + "&j=ImI",function(data){	
			if (exifvisible==1) {
				$('.exif').load(url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
		init_image_bar();			
		}); 
		
	return false;
	});
	
	// On clicking img
	$(".image_bar #img a").unbind();		
	$(".image_bar #img a").click(function(){
		window.open($(location).attr('search')+"&t=Big");
	return false;
	});	
	
	// On clicking get
	$(".image_bar #get a").unbind();		
	$(".image_bar #get a").click(function(){
		window.location=$(location).attr('search')+"&t=BDl";
	return false;
	});	

	// On clicking NEXT
	$(".image_bar #next a").unbind();		
	$(".image_bar #next a").click(function(){
		
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
		$('.image_panel').load(new_url + "&j=ImI",function(data){
			if (exifvisible==1) {
				$('.exif').load(new_url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
			if(slideshow_status == 1){
				hide_links();
			}
			init_image_bar();
		});	
		
	return false;
	});

	// On clicking PREV
	$(".image_bar #prev a").unbind();		
	$(".image_bar #prev a").click(function(){
		
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
		$('.image_panel').load(new_url + "&j=ImI",function(data){	
			if (exifvisible==1) {
				$('.exif').load(new_url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
			if(slideshow_status == 1){
				hide_links();
			}
		init_image_bar();
		});	
		
	return false;
	});
	
	$('#sscrollLeft').unbind();
	$('#sscrollLeft').hover(function(){
		scrollLeftLoop = setInterval(function() {
			$('#linear_panel').scrollLeft($('#linear_panel').scrollLeft()-120);
		}, 100);},function(){
		clearInterval(scrollLeftLoop);
	});
	
	$('#sscrollRight').unbind();
	$('#sscrollRight').hover(function(){
		scrollLeftLoop = setInterval(function() {
			$('#linear_panel').scrollLeft($('#linear_panel').scrollLeft()+120);
		}, 100);},function(){
		clearInterval(scrollLeftLoop);
	});	
	
	//~ $('video').mediaelementplayer({defaultVideoHeight: '100%'});

   
	$(".linear_panel").scrollTo($(".linear_panel .selected")).scrollTo("-="+($(".linear_panel").width()-145)/2);	
	init_slideshow_panel();	
	
}