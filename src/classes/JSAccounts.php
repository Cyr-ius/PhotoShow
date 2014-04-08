<?php
/**
 * This file implements the class JS Accounts.
 * 
 * PHP versions 4 and 5
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * JS Accounts
 *
 * Form for editing accounts. With JS.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class JSAccounts
{

	/// The accounts
	private $accounts;

	/// The groups
	private $groups;



	public function __construct(){
		$this->accounts = Account::findAll();

		$this->groups = Group::findAll();
	}

	public function toHTML(){
		$groupaccounts = array();
		echo "<div class='row-fluid'>";
		echo "<div class='span6'>";
		echo "<h3>".Settings::_("jsaccounts","accounts")."</h3>";
		
		foreach($this->accounts as $acc){
			echo "<div class='accountitem alert alert-info'>
					<form id='delacc-form' class='removeacc form-inline' action='WS_Account.delete' method='post'>
					<fieldset>
						<input type='hidden' name='name' value='".htmlentities($acc['login'], ENT_QUOTES ,'UTF-8')."'>
						<input class='btn btn-danger btn-mini' type='submit' value='x'>		
						<span class='name'>".$acc['login']."</span>
					</fieldset>
					</form>";
			echo "<div class='name hide'>".$acc['login']."</div>";					
			foreach($acc['groups'] as $g){
				$groupaccounts["$g"][] = $acc['login'];
				echo "<form id='rmgroup-form' style='display:inline;' method='post' action='WS_Account.remove_group'>
					<button type='submit' class='btn btn-mini'>
					<i class=' icon-trash'></i> <span class='groupname'>".htmlentities($g, ENT_QUOTES ,'UTF-8')."</span>
					</button>
					<input type='hidden' name='acc' value='".$acc['login']."'/>
					<input type='hidden' name='group' value='$g'/>
					</form>&nbsp;";				
			}
			echo "</div>";
		}
		echo "</div>";
		// Colonne droit - Gestion des groupes
		echo "<div class='span6'>";
		echo "<h3>".Settings::_("jsaccounts","groups")."</h3>";
		echo "<div class='newgroup well'>";
		echo "
		<form id='creategroup-form' class='addgroup form-inline' method='post' action='WS_Group.create'>";
		echo "<legend>".Settings::_("jsaccounts","addgroup")."</legend>\n";
		echo "<input id='groupname' class='input-medium' type='text' name='group' placeholder='".Settings::_("jsaccounts","groupname")."'>\n";
		echo "<input class='btn btn-primary' type='submit' value='".Settings::_("jsaccounts","addgroup")."'>\n";	
		echo "</form>\n";
		echo "</div>";
		foreach($this->groups as $g){
			$gn = $g['name'];
			echo "<div class='groupitem alert alert-success'>
					<form id='delgroup-form' class='removegroup' action='WS_Group.delete' method='post'>
						<input type='hidden' name='name' value='$gn'>
						<input class='btn btn-danger btn-mini' type='submit' value='x'>
						<span class='groupname'>".$gn."</span>
					</form>";
			echo "<div class='name hide'>".$gn."</div>";					
			if(isset($groupaccounts["$gn"])){
				foreach($groupaccounts["$gn"] as $g){
					echo "<form id='rmacc-form' style='display:inline;' method='post' action='WS_Account.remove_group'>
						<button type='submit' class='btn btn-mini'>
						<i class=' icon-trash'></i> <span class='accname'>".htmlentities($g, ENT_QUOTES ,'UTF-8')."</span>
						</button>
						<input type='hidden' name='acc' value='$g'/>
						<input type='hidden' name='group' value='$gn'/>
						</form>&nbsp;";
				}
			}
			echo "</div>";
		}
		
		echo "</div>\n";
		echo "</div>\n";
	}

}