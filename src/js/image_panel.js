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
	$('#button_exif,#button_downloadorig,#button_vieworig').show();
	$('#slideshow').css( "display", "block");
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
	

	
	$('.linear_panel').mCustomScrollbar("scrollTo",".thumbnails li.selected");
	$('img.lazy').lazyload('update');
	init_image_bar();
	
	// On mousewheelling
	//~ $(".linear_panel").mousewheel(function(event,delta){
		//~ if($(".linear_panel").is(":visible")){
			//~ this.scrollLeft -= delta * 30;
			//~ event.preventDefault();
		//~ }
	//~ });
	
 }	
 
 /**
 * Initialise the image bar
 */
function init_image_bar(){


	$('#spacer').heightauto();
	
	$('.bigimage,.bigvideo').hover(function(){
		$('#next,#prev').show();
	},function(){
		$('#next,#prev').hide()
	});

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
		$('#current').parent().load(url + "&j=ImI",function(data){	
			if (exifvisible==1) {
				$('.exif').load(url+"&t=Exif",function() {
					$('.exif').show();
				});
			}
		init_image_bar();			
		}); 
		
	return false;
	});

	// On clicking NEXT
	$(".image_panel #next").unbind();		
	$(".image_panel #next").click(function(){
		
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
		$.get(new_url+'&j=ImI',function(data){
			$w = $('#current').width();
			$('.bigimage').append($(data).attr('id','next-1').hide());
			$('#next-1').css({"left":$w+'px',"right":'-'+$w+'px'}).show();
			$('#next-1').animate({'left':0,'right':0},300,function(){
				 $('#current').remove();
				$('#next-1').attr('id','current');
				init_image_bar();
			});

		});

		
		//~ var iw = $('#current').width();
		//~ $('#myimg').append('<li id="next-1" style="display:inline-block"><img src="'+new_url+'&t=Img"></li>');
		//~ $('#myimg').parent().stop(true,false).animate({'left':'-'+(iw)+'px'},function(){
						
			//~ $('#current').remove();
			//~ $('#myimg').parent().css('left','0');
			//~ $('#next-1').attr('id','current');
			
			//~ });

		//~ });

		//~ $('.image_panel').load(new_url + "&j=ImI",function(data){
			//~ if (exifvisible==1) {
				//~ $('.exif').load(new_url+"&t=Exif",function() {
					//~ $('.exif').show();
				//~ });
			//~ }
			//~ if(slideshow_status == 1){
				//~ hide_links();
			//~ }
			//~ init_image_bar();
		//~ });	
		
	return false;
	});

	// On clicking PREV
	$(".image_panel #prev").unbind();		
	$(".image_panel #prev").click(function(){
		
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
		$.get(new_url+'&j=ImI',function(data){
			$w = $('#current').width();
			$('.bigimage').append($(data).attr('id','next-1').hide());
			$('#next-1').css({"left":'-'+$w+'px',"right":$w+'px'}).show();
			$('#next-1').animate({'left':0,'right':0},300,function(){
				 $('#current').remove();
				$('#next-1').attr('id','current');
				init_image_bar();
			});

		});
		
		
		
		//~ $('.image_panel').load(new_url + "&j=ImI",function(data){	
			//~ if (exifvisible==1) {
				//~ $('.exif').load(new_url+"&t=Exif",function() {
					//~ $('.exif').show();
				//~ });
			//~ }
			//~ if(slideshow_status == 1){
				//~ hide_links();
			//~ }
		//~ init_image_bar();
		//~ });	
		
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