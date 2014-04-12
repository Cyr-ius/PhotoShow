/**
 * This file implements menu-related JS.
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
// Dummy function
}

function init_plupload(){
// Dummy function
}

function init_infos(){
// Dummy function
$('#button_download').attr('href',$(location).attr('search')+"&t=Zip");
$('#button_comm').attr('data-href',$(location).attr('search')+"&t=Com");
}

function init_list(){
// Dummy function
}

function init_textinfo(){
// Dummy function
}

function init_menu(){
	
	$('.menu').mCustomScrollbar('destroy');
	scrollbar(".menu",false);

	/**
	* Clicking on an item in the menu
	*/	
	$(".submenu a").unbind();
	$(".submenu a").click(function(){
		// Change selected item
		if ($(this).parent().parent().hasClass('root')) {
			$(".submenu .selected").find('li').removeClass("active selected");
		}
		$(this).parent().parent('ul').find('li').removeClass("active selected");
		$(this).parent().addClass("active selected");	
		url = $(this).attr("href");
		update_url(url);
		$('.bigpanel,.panel').hide();
		$('.loading').show();
		$('.panel').load(url+"&j=Pan",function(){
			$('.loading').hide();
			$('.panel').show('fast',function(){
				$('.panel').scrollTop(0);
				init();
			});
		});
	return false;
	});
	/**
	* Clicking on an album in the panel
	*/
	$(".albums a").unbind();
	$(".albums a").click(function(){
		url = $(this).attr("href");
		update_url(url);
		$('.bigpanel,.panel').hide();
		$('.loading').show();
		$('.menu').load(url+"&j=Men",init_menu);
		$('.panel').load(url+"&j=Pan",function(){
			$('.loading').hide();
			$('.panel').show('fast',function(){
				$('.panel').scrollTop(0);
				init();}
			);
		});	
	return false;
	});
}
 
function update_url(url,name){
	if(typeof history.pushState == 'function') { 
		var stateObj = { foo: "bar" };
		history.pushState(stateObj, "PhotoShow - " + name, url);
	}

	//Select Item in linear_panel
	if ($('.linear_panel a[href$="'+url+'"]').size()) {
		$('.linear_panel .selected').removeClass("active selected");
		$('.linear_panel a[href$="'+url+'"]').parent().addClass("active selected");
	}
	//Select Item in panel
	if ($('.panel a[href$="'+url+'"]').size()) {
		$('.panel .selected').removeClass("active selected");
		$('.panel a[href$="'+url+'"]').parent().addClass("active selected");
	}
	
}
