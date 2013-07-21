<?php
/**
 * This file implements the class MainPage.
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
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright 2011 Thibaud Rohmer + 2013 Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
/**
 * MainPage
 *
 * This is the page containing the Boards.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright Thibaud Rohmer + Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
<<<<<<< HEAD

class MainPage extends Page
{
	
	/// True if the image div should be visible
	private $image_div = false;
	
	/// Boardpanel object
	private $panel;
	
	/// Boards class;
	private $panel_class;

	/// Menubar object
	private $menubar;
	
	/// Imagepanel object
	private $image_panel;

	/// Image_panel class
	private $image_panel_class;

	/// Imagepanel object
	private $menu;
	
	///Modal
	private $mt;
	private $ma;
	
	///Script
	private $scripts;

	/**
	 * Creates the page
	 *
	 * @author Thibaud Rohmer
	 */
	public function __construct(){	

		/// Check how to display current file
		if(is_file(CurrentUser::$path)){
			$this->bigpanel_visible			=	"";			
			$this->image_panel			=	new ImagePanel(CurrentUser::$path);
			$this->panel					=	new Board(dirname(CurrentUser::$path));
			$this->linear_panel				=	new Linear_panel(CurrentUser::$path);
			$this->panel_visible			=	"hide";
		}else{
			$this->bigpanel_visible			=	"hide";						
			$this->image_panel			=	new ImagePanel();		
			$this->panel					=	new Board(CurrentUser::$path);
			$this->linear_panel				=	new Linear_panel(CurrentUser::$path);			
			$this->panel_visible			=	"";			
		}

		/// Create MenuBar
		$this->menubar 		= 	new MenuBar();

		/// Menu
		$this->menu			=	new Menu();

	}
	
	/**
	 * Display page on the website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML($menu=true){
		//Navbar
		if ($menu){ $this->menubar->toHTML();}

		echo "<div id='content' class='container-fluid'>";
			echo "<div class='row-fluid'>";
				/// Start menu
				echo "<div id='menu' class='span2 well menu'>";
				$this->menu->toHTML();
				echo "</div>\n";
				/// Stop menu		
				echo "<div class='span10 loading hide'></div>";
				echo "<div class='span10 container-fluid bigpanel $this->bigpanel_visible'>\n";
					/// ImagePanel
					echo "<div id='image_panel' class='image_panel'>\n";
					$this->image_panel->toHTML();
					echo "</div>\n";
					///Linear_panel
					echo "<div id='linear_panel' class='linear_panel'><ul class='thumbnails'>";
					$this->linear_panel	->toHTML();
					echo "</ul></div>";
				echo "</div>\n";
				///Panel (include boardheader(title+button) , album , images , videos , comments)
				echo "<div class='span10 container-fluid panel $this->panel_visible'>\n";
					if (Index::$welcome) {
						echo "<h2>Welcome to Photoshow </h2>";
						echo "<div id='welcome' class='well'>Please, clic on the link below <br/><br/><a href='#' data-href='?t=Reg' data-toggle='modal' data-target='#myModal' data-title='".Settings::_("menubar","register")."' data-type='register'><i class='icon-pencil'></i> ".Settings::_("menubar","register")." the first account Administrator</a>";
						echo "</div>";		
					} else {
						$this->panel->toHTML();
					}
				echo "</div>\n";	
				
			echo "</div>\n";					
		echo "</div>";
	}
=======
class MainPage extends Page {
    /// True if the image div should be visible
    private $image_div = false;
    /// Boardpanel object
    private $panel;
    /// Specific header content
    private $header_content;
    /// Boards class;
    private $panel_class;
    /// Menubar object
    private $menubar;
    /// Imagepanel object
    private $image_panel;
    /// Image_panel class
    private $image_panel_class;
    /// Imagepanel object
    private $menu;
    /// Infos
    private $infos;
    /**
     * Creates the page
     *
     * @author Thibaud Rohmer
     */
    public function __construct() {
        try {
            $settings = new Settings();
        }
        catch(FileException $e) {
            // If Accounts File missing... Register !
            $this->header();
            new RegisterPage();
            exit;
        }
        /// Check how to display current file
        if (is_file(CurrentUser::$path)) {
            $this->image_panel = new ImagePanel(CurrentUser::$path);
            $this->image_panel_class = "image_panel";
            $this->panel = new Board(dirname(CurrentUser::$path));
            $this->panel_class = "linear_panel";
            $this->header_content = $this->image_panel->page_header;
        } else {
            $this->image_div = false;
            $this->image_panel = new ImagePanel();
            $this->image_panel_class = "image_panel hidden";
            $this->panel = new Board(CurrentUser::$path);
            $this->panel_class = "panel";
            $this->header_content = $this->panel->page_header;
        }
        /// Create MenuBar
        $this->menubar = new MenuBar();
        /// Menu
        $this->menu = new Menu();
        $this->infos = new Infos();
    }
    /**
     * Display page on the website
     *
     * @return void
     * @author Thibaud Rohmer
     */
    public function toHTML() {
        $this->header($this->header_content);
        echo "<body>";
        echo "<div id='container'>\n";
        $this->menubar->toHTML();
        echo "<div id='page'>\n";
        /// Start menu
        echo "<div id='menu' class='menu'>\n";
        $this->menu->toHTML();
        echo "</div>\n";
        if (CurrentUser::$admin || CurrentUser::$uploader) {
            echo "<div class='bin'><img src='" . Settings::$self_path . "/inc/bin.png' alt=" . Settings::_("bin", "delete") . ">" . Settings::_("bin", "delete") . "</div>";
        }
        /// Stop menu
        echo "<div id='menu_hide'></div>";
        echo "<div class='center selectzone'>";
        /// Start Panel
        echo "<div class='$this->panel_class'>\n";
        $this->panel->toHTML();
        echo "</div>\n";
        /// Stop Panel
        /// Start ImagePanel
        echo "<div class='$this->image_panel_class'>\n";
        $this->image_panel->toHTML();
        echo "</div>\n";
        /// Stop ImagePanel
        echo "</div>\n";
        echo "<div id='infos_hide'></div>";
        echo "<div class='infos'>\n";
        $this->infos->toHTML();
        echo "</div>\n";
        echo "</div>\n";
        echo "</div>\n";
        if (Settings::$hide_menu) {
            echo '
		<script language="javascript" type="text/javascript">
		menu_hide();
		</script>
		';
        }
        if (Settings::$hide_infos) {
            echo '
		<script language="javascript" type="text/javascript">
		info_hide();
		</script>
		';
        }
        echo "</body></html>";
    }
>>>>>>> 3fbb242568a4ddc60dee5d2c019391f366ad63d4
}
?>
