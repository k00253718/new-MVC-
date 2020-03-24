<?php
/**
 * Class: Navigation
 * This class is used to generate navigation menu items in an an array for the view.
 * 
 * It uses the logged in status, user type  and currently selected pageID to determine which items 
 * are included in the menu for that specific page.
 *
 * It also provides examples of how to generate drop down menus
 * 
 * @author gerry.guinane
 * 
 */

class Navigation extends Model{
    
//CLASS properties
        private $pageID;   //String: currently selected page
        private $menuNav;  //Array: array of menu items    
        private $user;     //User: object of User class
        
//CLASS methods	

	//METHOD: constructor 
	function __construct($user,$pageID) {   
            parent::__construct($user->getLoggedInState());
            $this->user=$user;
            $this->pageID=$pageID;
            $this->setmenuNav();
	}
        //END METHOD: constructor 
      
        //METHOD: setmenuNav()
        public function setmenuNav(){//set the menu items depending on the users selected page ID
            
            //empty string for menu items
            $this->menuNav='';
            
            //dropdown menu items for MODULES
            $dropdownMenuModules='<li class="dropdown">';
            $dropdownMenuModules.='<a class="dropdown-toggle" data-toggle="dropdown" href="#">Modules<span class="caret"></span></a>';
            $dropdownMenuModules.='<ul class="dropdown-menu">';
            $dropdownMenuModules.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=modulesViewEdit">View/Edit</a></li>';
            $dropdownMenuModules.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=modulesAdd">Add</a></li>';
            $dropdownMenuModules.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=modulesDelete">Delete</a></li>';
            $dropdownMenuModules.='</ul></li>'; 
                                         
            //dropdown menu items for My Account
            $dropdownMenuAccount='<li class="dropdown">';
            $dropdownMenuAccount.='<a class="dropdown-toggle" data-toggle="dropdown" href="#">My Account<span class="caret"></span></a>';
            $dropdownMenuAccount.='<ul class="dropdown-menu">';
            $dropdownMenuAccount.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=accountEdit">Edit My Details</a></li>';
            $dropdownMenuAccount.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=accountPasswordChange">Change My Password</a></li>';
            $dropdownMenuAccount.='</ul></li>'; 
            
            if($this->loggedin){  //if user is logged in           
                if ($this->user->getUserType()==='ADMINISTRATOR'){  //check if the logged in user is an ADMINISTRATOR
                    switch ($this->pageID) {
                    case "home":
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_1">Menu Item 1</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_2">Menu Item 2</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    case "menuItem_1":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_1">Menu Item 1</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_2">Menu Item 2</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    case "menuItem_2":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_1">Menu Item 1</a></li>';
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_2">Menu Item 2</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;                                        
                    case "accountEdit":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    case "accountPasswordChange":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;                  
                    default:
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_1">Menu Item 1</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem_2">Menu Item 2</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    }//end switch
                }
                else{  //Logged in user must be a CUSTOMER
                    switch ($this->pageID) {
                    case "home":
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;                                   
                    case "mygrades":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;

                    case "accountPasswordChange":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';  
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                
                    case "logout":  //DUMMY CASE - this case is not actually needed!!
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        break;
                    
                    default:
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuAccount";  //the My Account drop down menu
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    }//end switch
                }
            }
            else{ //user is NOT logged in  
                  switch ($this->pageID) {
                    case "home":
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=register">Register</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=login">Login</a></li>';
                        break;
                    case "register":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=login">Login</a></li>';
                        break;         
                    case "login":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=register">Register</a></li>';
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=login">Login</a></li>';
                        break;  
                    default:
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=register">Register</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=login">Login</a></li>';
                        break;
            }
            }   
        } 
        //END METHOD: setmenuNav()
        
        //METHOD: getMenuNav()
        public function getMenuNav(){return $this->menuNav;}    
        //END METHOD: getMenuNav()
  
}//end class
        