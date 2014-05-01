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
	$("#next").click();
}

function start_slideshow(){

	$('.image_bar').css("margin","0");
	$('.menu').hide("slide",{direction:"left"},600);
	$('.linear_panel').hide("slide",{direction:"down"},600);
	$('#menubar').hide("slide",{direction:"up"},600);
	$(".bigpanel").animate({left:0,top:0,right:0,bottom:0},600);
	$('.bigpanel').css("position","static");
	$(".image_panel").css( "margin","30px");
	$(".image_panel").css( "bottom","0");
	if (fullScreenApi.supportsFullScreen) {
		$('.bigpanel').requestFullScreen();
		fullscreen_status = 1;
	}
	slideshow_status = 1;
	timer = setInterval('run_slideshow()',$('#timeshow').val()*1000);
}

function pause_slideshow(){
	slideshow_status = 0;
	clearInterval(timer);
}

function stop_slideshow(){
	if (fullScreenApi.isFullScreen) {
		fullScreenApi.cancelFullScreen();
		fullscreen_status = 0;
	}	
	pause_slideshow();
	$('.image_bar').css("margin","");
	$('.bigpanel').css( "left","");
	$('.bigpanel').css( "top","");
	$('.bigpanel').css( "right","");
	$('.bigpanel').css( "bottom","");
	$('.bigpanel').css("position","");
	$(".image_panel").css( "margin","");
	$(".image_panel").css( "bottom","");
	$('.menu').show("slide",{direction:"right"},600);
	$('.linear_panel').show("slide",{direction:"up"},600);
	$('#menubar').show("slide",{direction:"down"},600);

}

function toggle_slideshow(){
	if(slideshow_status == 1){
		pause_slideshow();
	}else{
		start_slideshow();
	}
}

function init_slideshow_panel(){
	
	$("#slideshow").unbind();
	$("#slideshow").click(function(){
		toggle_slideshow();
		return false;
	});
}
