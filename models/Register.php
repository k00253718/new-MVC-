<?php
/**
 * Class: Register
 *
 * This class processes a user registration form 
 * 
 * 
 * @author gerry.guinane
 * 
 */

class Register extends Model{
    
//CLASS properties
    private $db;                //MySQLi object: the database connection ( 
    private $user;              //object of User class
    private $pageTitle;         //String: containing page title
    private $pageHeading;       //String: Containing Page Heading
    private $postArray;         //Array: Containing copy of $_POST array
    private $panelHead_1;       //String: Panel 1 Heading
    private $panelHead_2;       //String: Panel 2 Heading
    private $panelHead_3;       //String: Panel 3 Heading
    private $panelContent_1;    //String: Panel 1 Content
    private $panelContent_2;    //String: Panel 2 Content     
    private $panelContent_3;    //String: Panel 3 Content        
        
//CLASS methods	

	//METHOD: constructor 
	function __construct($postArray,$pageTitle,$pageHead,$database, $user)
	{   
            parent::__construct($user->getLoggedinState());
            
            $this->db=$database;

            $this->user=$user;
            
            //set the PAGE title
            $this->setPageTitle($pageTitle);
            
            //set the PAGE heading
            $this->setPageHeading($pageHead);

            //get the postArray
            $this->postArray=$postArray;
            
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
        
        //setter methods
        
        //headings
        public function setPageTitle($pageTitle){ //set the page title    
                $this->pageTitle=$pageTitle;
        }  //end METHOD -   set the page title       
        public function setPageHeading($pageHead){ //set the page heading  
                $this->pageHeading=$pageHead;
        }  //end METHOD -   set the page heading
        
        //Panel 1
        public function setPanelHead_1(){//set the panel 1 heading
            $this->panelHead_1='<h3>Registration Form</h3>';       
        }//end METHOD - //set the panel 1 heading
        public function setPanelContent_1(){//set the panel 1 content                                  
             $this->panelContent_1 = file_get_contents('forms/form_register.html');  //this reads an external form file into the string 
        }//end METHOD - //set the panel 1 content        

        //Panel 2
        public function setPanelHead_2(){ //set the panel 2 heading       
            
            $this->panelHead_2='<h3>Registration Result</h3>'; 
            
        }//end METHOD - //set the panel 2 heading     
        public function setPanelContent_2(){//set the panel 2 content
            //process the registration button
            if (isset($this->postArray['btn'])){  //check the button is pressed
                
                if ($this->postArray['Pass1']===$this->postArray['Pass2']){  //verify passwords match
                    //process the registration data
                    $this->panelContent_2='Passwords Match<br>';
                    $this->panelContent_2.='email   : '.$this->postArray['email'].'<br>';
                    $this->panelContent_2.='Firstname : '.$this->postArray['FirstName'].'<br>';
                    $this->panelContent_2.='Lastname  : '.$this->postArray['LastName'].'<br>';
                    $this->panelContent_2.='Password1 : '.$this->postArray['Pass1'].'<br>';
                    $this->panelContent_2.='Password2 : '.$this->postArray['Pass2'].'<br>';
                    
                    if ($this->user->register($this->postArray)){  //call the user::registration() method. 
                        $this->panelContent_2.='REGISTRATION SUCCESSFUL - please log in<br>';
                    }
                    else{
                        $this->panelContent_2.='REGISTRATION NOT SUCCESSFUL - '.$this->user->getErrMsg().'<br>';
                        
                    }
                    
                }
                else{
                    $this->panelContent_2='Passwords DONT Match<br>';
                    $this->panelContent_2.='email   : '.$this->postArray['email'].'<br>';
                    $this->panelContent_2.='Firstname : '.$this->postArray['FirstName'].'<br>';
                    $this->panelContent_2.='Lastname  : '.$this->postArray['LastName'].'<br>';
                    $this->panelContent_2.='Password1 : '.$this->postArray['Pass1'].'<br>';
                    $this->panelContent_2.='Password2 : '.$this->postArray['Pass2'].'<br>';                   
                }
            }
            else{
                $this->panelContent_2='Please enter details in the form';
            }           
        }//end METHOD - //set the panel 2 content  
        
        //Panel 3
        public function setPanelHead_3(){ //set the panel 3 heading
            if($this->loggedin){
                $this->panelHead_3='<h3>Panel 3</h3>';   
            }
            else{        
                $this->panelHead_3='<h3>Panel 3</h3>'; 
            }
        } //end METHOD - //set the panel 3 heading
        public function setPanelContent_3(){ //set the panel 2 content
            if($this->loggedin){
                $this->panelContent_3='Panel 3 content - unser construction (user logged in)';
            }
            else{        
                $this->panelContent_3='Panel 3 content - unser construction (user not logged in)';
            }
        }  //end METHOD - //set the panel 2 content        
       

        //getter methods
        public function getPageTitle(){return $this->pageTitle;}
        public function getPageHeading(){return $this->pageHeading;}
        public function getMenuNav(){return $this->menuNav;}
        public function getPanelHead_1(){return $this->panelHead_1;}
        public function getPanelContent_1(){return $this->panelContent_1;}
        public function getPanelHead_2(){return $this->panelHead_2;}
        public function getPanelContent_2(){return $this->panelContent_2;}
        public function getPanelHead_3(){return $this->panelHead_3;}
        public function getPanelContent_3(){return $this->panelContent_3;}
        public function getUser(){return $this->user;}

        
}//end class
        