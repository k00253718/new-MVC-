<?php
/**
 * Class: Home
 * This class is used to generate text content for the HOME page view.
 * 
 * This is the 'landing' page for the web application. 
 * 
 * It handles both logged in and not logged in usee cases and ADMINISTRATOR and CUSTOMER user types
 *
 * @author gerry.guinane
 * 
 */

class Home extends Model{
    
//CLASS properties
        private $db;                //MySQLi object: the database connection ( 
        private $user;              //object of User class
        private $pageTitle;         //String: containing page title
        private $pageHeading;       //String: Containing Page Heading
        private $panelHead_1;       //String: Panel 1 Heading
        private $panelHead_2;       //String: Panel 2 Heading
        private $panelHead_3;       //String: Panel 3 Heading
        private $panelContent_1;    //String: Panel 1 Content
        private $panelContent_2;    //String: Panel 2 Content     
        private $panelContent_3;    //String: Panel 3 Content
    
//CLASS methods
        //METHOD: constructor 
	function __construct($user,$pageTitle,$pageHead){   
            parent::__construct($user->getLoggedInState());
            $this->user=$user;

            //set the PAGE title
            $this->setPageTitle($pageTitle);
            
            //set the PAGE heading
            $this->setPageHeading($pageHead);

            //set the FIRST panel content
            $this->setPanelHead_1();
            $this->setPanelContent_1();

            //set the DECOND panel content
            $this->setPanelHead_2();
            $this->setPanelContent_2();
        
            //set the THIRD panel content
            $this->setPanelHead_3();
            $this->setPanelContent_3();
	} 
        //END METHOD: constructor 
      
        //SETTER METHODS
        
        //Headings
        public function setPageTitle($pageTitle){ //set the page title    
                $this->pageTitle=$pageTitle;
        }  //end METHOD -   set the page title       
        public function setPageHeading($pageHead){ //set the page heading  
                $this->pageHeading=$pageHead;
        }  //end METHOD -   set the page heading
        
        //Panel 1
        public function setPanelHead_1(){//set the panel 1 heading
                $this->panelHead_1='<h3>MVC Template Example</h3>';
        }//end METHOD - //set the panel 1 heading
        public function setPanelContent_1(){//set the panel 1 content
            if($this->loggedin){                
                if ($this->user->getUserType()==='ADMINISTRATOR'){ //A Lecturer is logged in 
                    $this->panelContent_1='<h4>Overview</h4>';
                    $this->panelContent_1.='<p>This is a TEMPLATE for use in Assignment A01</p>'; 
                    $this->panelContent_1.='<p>You are currently logged in as an ADMINISTRATOR.';
                }
                else{//A Student is logged in 
                    $this->panelContent_1='<h4>Overview</h4>';
                    $this->panelContent_1.='<p>This is a TEMPLATE for use in Assignment A01</p>'; 
                    $this->panelContent_1.='<p>You are currently logged in as a CUSTOMER.';
                }
            }
            else{ //User is not logged in
                $this->panelContent_1='<h4>Overview</h4>';
                $this->panelContent_1.='<p>This is a TEMPLATE for use in Assignment A01</p>'; 
                $this->panelContent_1.='<p>You must log in to use this system.';  
            }
        }//end METHOD - //set the panel 1 content        

        //Panel 2
        public function setPanelHead_2(){ //set the panel 2 heading
            if($this->loggedin){
                $this->panelHead_2='<h3>Welcome</h3>';
            }
            else{        
                $this->panelHead_2='<h3>Login required</h3>';
            }
        }//end METHOD - //set the panel 2 heading    
        public function setPanelContent_2(){//set the panel 2 content
            //get the Middle panel content
            if($this->loggedin){
                
                if ($this->user->getUserType()==='ADMINISTRATOR'){ //A Lectuirer is logged in
                    $this->panelContent_2='Thank you '.$this->user->getUserFirstName() .' for logging in successfully as ADMINISTRATOR. Please use the links above.<br><br>Don\'t forget to logout when you are done.';
                }
                else{ //a Student is logged in
                    $this->panelContent_2='Thank you '.$this->user->getUserFirstName() .' for logging in successfully as a CUSTOMER. Please use the links above.<br><br>Don\'t forget to logout when you are done.';
                }    
            }
            else{  //User is not logged in        
                $this->panelContent_2='You are required to login or register - Please use the links above';
            }
        }//end METHOD - //set the panel 2 content  
        
        //Panel 3
        public function setPanelHead_3(){ //set the panel 3 heading
            if($this->loggedin){
                $this->panelHead_3='<h3>Panel 3 Head</h3>';
            }
            else{        
                $this->panelHead_3='<h3>Panel 3 Head</h3>';
            } 
        } //end METHOD - //set the panel 3 heading  
        public function setPanelContent_3(){ //set the panel 2 content
            if($this->loggedin){  
                $this->panelContent_3='Panel 3 content';
            }
            else{        
                $this->panelContent_3='Panel 3 content';
            } 
        }  //end METHOD - //set the panel 2 content        
       
         //GETTER METHODS
        public function getPageTitle(){return $this->pageTitle;}
        public function getPageHeading(){return $this->pageHeading;}
        public function getMenuNav(){return $this->menuNav;}
        public function getPanelHead_1(){return $this->panelHead_1;}
        public function getPanelContent_1(){return $this->panelContent_1;}
        public function getPanelHead_2(){return $this->panelHead_2;}
        public function getPanelContent_2(){return $this->panelContent_2;}
        public function getPanelHead_3(){return $this->panelHead_3;}
        public function getPanelContent_3(){return $this->panelContent_3;}
        //END GETTER METHODS        
        
}//end class
        