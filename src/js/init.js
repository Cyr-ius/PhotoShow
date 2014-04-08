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
 


function init_scripts(){
	var js = JSON.stringify({"jsonrpc":"2.0","method":"WS_Script.list_scripts","params":[],"id":"1"});
	$.ajax({url:'/',data:js,type:'POST',dataType:"json",contentType: "application/json"})
	.done(function(data){
		if (!data.error) {
			for(var val in data.result){
 			var script = document.createElement("script");
			    script.type = "text/javascript";
			    script.src=data.result[val];
			    script.onreadystatechange= function () {
			      if (this.readyState == 'complete') init();
			    }
				
				$("#scripts").append(script);
			}
		}
	});
}

$("document").ready(function(){
	exifvisible = 0;
	viewlist = 0;
	init_scripts();
	//init();
	if ($(".menu .selected:last").length > 0) $(".menu").scrollTo($(".menu .selected:last"));

});
