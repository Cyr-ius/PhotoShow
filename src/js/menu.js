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
//Json with reload page
$('#logins-form').unbind();
$('#logins-form').submit(function(){
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
/**
* Clicking on an item in the menu
*/
$(".submenu a").unbind();
$(".submenu a").click(function(){
// Change selected item
if ($(this).parent().parent().hasClass('root')) {
	$(".submenu .selected").find('li').removeClass("selected");
}
$(this).parent().parent('ul').find('li').removeClass("selected");
$(this).parent().addClass("selected");	
url = $(this).attr("href");
//~ update_url(hr,$(this).text());	
$('.panel').hide();
$('.loading').show();
$.get(url+"&j=Pan",function(data) {
	$(".panel").html(data);
		update_url(url);	
		init_panel();
		$('.loading').hide();
		$('.panel').show();
		$('.center').scroll();
	});

return false;
});

$(".albums a").unbind();
$(".albums a").click(function(){
// Change selected item
$(".panel .selected").removeClass("selected");
$(this).parent().addClass("selected");		
url = $(this).attr("href");
//update_url(hr,$(this).text());	
$('.loading').show();
$('.panel').hide();
$.get(url+"&j=Pag",function(data) {
	update_url(url);
	$('#content').html(data);
	init_panel();
	$('.menu').mCustomScrollbar({scrollButtons:{enable:true}});
	$('.loading').hide();
	$('.panel').show();
	$('.center').scroll();
	});
return false;
});

}
 
function update_url(url,name){
	if(typeof history.pushState == 'function') { 
		var stateObj = { foo: "bar" };
		history.pushState(stateObj, "PhotoShow - " + name, url);
	}
}
