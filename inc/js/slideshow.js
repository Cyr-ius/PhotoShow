/**
 * This file implements the slideshow.
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

var slideshow_status = 0;
var fullscreen_status = 0;
var timer = 0;

function run_slideshow(){
	$("#next a").click();
}

function start_slideshow(){
	if (fullScreenApi.supportsFullScreen) {
		$('#image_panel').requestFullScreen();
		fullscreen_status = 1;
	}
	slideshow_status = 1;
	timer = setInterval('run_slideshow()',3000);
	$('#image_bar').css('margin-bottom','150px');
	 $(".image_panel").animate({height:'100%'},600);
	 $(".image_panel").animate({width:'100%'},600);
	$(".image_panel").css("position","fixed");
	$(".image_panel").css("z-index",10000);
	$(".image_panel").css( "background","none repeat scroll 0 0 black");
	$(".image_panel").css( "height","100%");
	$(".image_panel").css( "width","100%");
	$(".image_panel").css( "left","0");
	$(".image_panel").css( "top","0");	
	$("#image_big").css( "height","100%");	
	$(".center").css( "z-index",10000);
	$('.linear_panel,.menu,.menubar').hide();
	$('#image_bar #linear').css('display','inline-block');	
	hide_links();
}

function pause_slideshow(){
	show_links();
	$('#slideshow i').removeClass('icon-pause').addClass('icon-play');
	slideshow_status = 0;
	clearInterval(timer);
}

function stop_slideshow(){
	if (fullScreenApi.isFullScreen) {
		fullScreenApi.cancelFullScreen();
	}	
	slideshow_status = 0;
	fullscreen_status = 0;
	clearInterval(timer);
	$(".image_panel").css("position","");
	$(".image_panel").css("z-index",0);
	$(".image_panel").css( "background","");
	$(".image_panel").css( "left","");
	$(".image_panel").css( "top","");	
	$(".image_panel").css( "height","");
	$(".image_panel").css( "width","");	
	$("#image_big").css( "height","100%");
	$(".center").css( "z-index",'-1');
	$('.linear_panel,.menu,.menubar').show();	
	$('#slideshow i').removeClass('icon-pause').addClass('icon-play');
	$('#image_bar').css('margin-bottom','');
	show_links();
}

function toggle_slideshow(){
	if(slideshow_status == 1){
		pause_slideshow();
	}else{
		start_slideshow();
	}
}

function init_slideshow_panel(){
	
	if(fullscreen_status == 1){
		$('#image_bar').css('margin-bottom','150px');
		$('#image_bar #linear').css('display','inline-block');
	}
	
	$("#slideshow").unbind();
	$("#slideshow").click(function(){
		toggle_slideshow();
		return false;
	});

	$("#back,#linear").click(function(){
		stop_slideshow();
		$(".bigimage a").unbind();
		$('#image_bar #linear').hide();
		return false;
	}); 
}

function show_links(){
	//~ $('#slideshow i').removeClass('icon-pause').addClass('icon-play');
	//~ $('#stop').hide();
	$('#image_bar #prev').show();
	$('#image_bar #linear').show();
	$('#image_bar #back').show();
	$('#image_bar #slideshow').show();
	$('#image_bar #next').show();
	$('#image_bar #img').show();
	$('#image_bar #get').show();
}

function hide_links(){
	
	$(".bigimage a").unbind();
	$(".bigimage a").bind('click',function(e){e.preventDefault();});
	$(".bigimage a").bind('mouseover',function(){show_links()});
	$('#slideshow i').removeClass('icon-play').addClass('icon-pause');
	$('#image_bar #prev').hide();
	$('#image_bar #linear').hide();
	$('#image_bar #back').hide();
	$('#image_bar #slideshow').hide();
	$('#image_bar #next').hide();
	$('#image_bar #img').hide();
	$('#image_bar #get').hide();
}
