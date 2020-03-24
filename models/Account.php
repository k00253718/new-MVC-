<?php
/**
 * Class: Account
 *
 * @author gerry.guinane
 * 
 */

class Account extends Model{
    
//CLASS properties
        private $db;                //MySQLi object: the database connection ( 
        private $user;              //object of User class
        private $pageID;            //String: currently selected page
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
	function __construct($postArray,$pageTitle,$pageHead,$database, $user, $pageID)
	{   
            parent::__construct($user->getLoggedinState());
            
            $this->db=$database;
            $this->pageID=$pageID;
            $this->user=$user;     
            
            //set the PAGE title
            $this->setPageTitle($pageTitle);
            
            //set the PAGE heading
            $this->setPageHeading($pageHead);

            //get the postArray
            $this->postArray=$postArray;
            
            //set the SECOND panel content
            $this->setPanelHead_2();
            $this->setPanelContent_2();
            
            //set the FIRST panel content
            $this->setPanelHead_1();
            $this->setPanelContent_1();
    
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
           switch ($this->pageID) { //check which button is pressed           
                case 'accountEdit':
                      $this->panelHead_1='<h3>Edit My Account Details</h3>';   
                    break;
                case 'accountPasswordChange':
                    $this->panelHead_1='<h3>Change My Password</h3>';   
                    break;
                default:
                      $this->panelContent_1='Invalid Choice';
                    break;
                }  
        }//end METHOD - //set the panel 1 heading        
        public function setPanelContent_1(){//set the panel 1 content 
           $this->panelContent_1='';
           switch ($this->pageID) { //check which button is pressed  
               
                case 'accountEdit':
                    //generate the edit form 
                    $this->panelContent_1.=$this->user->recordEditForm();              
                    break;
                    
                case 'accountPasswordChange':
                    $this->panelContent_1 = file_get_contents('forms/form_user_password_change.html');  //this reads an external form file into the string 
                    break;
                
                default:
                      $this->panelContent_1 = 'Error';
                    break;
                }          
        }//end METHOD - //set the panel 1 content        

        //Panel 2
        public function setPanelHead_2(){ //set the panel 2 heading       
            
            $this->panelHead_2='<h3>Result</h3>'; 
            
        }//end METHOD - //set the panel 2 heading             
        public function setPanelContent_2(){//set the panel 2 content
            if (isset($this->postArray['btn'])){ //process the SAVE button               
                //check which button value - SAVE or CHANGE PASSWORD
                switch ($this->postArray['btn']){
                    case 'accountSave':
                        //retrieve values from the edit form
                        $id=$this->db->real_escape_string($this->postArray['User_ID']);
                        $firstName=$this->db->real_escape_string($this->postArray['FirstName']);
                        $lastName=$this->db->real_escape_string($this->postArray['LastName']);  
                        $mobile=$this->db->real_escape_string($this->postArray['mobile']);   

                        //generate the SQL
                        $sql="UPDATE user SET FirstName = '$firstName',LastName = '$lastName',mobile = '$mobile' WHERE id = '$id'";

                        //call the user update record method to save the edited data   
                        if($this->user->saveUpdate($sql)){
                            $this->panelContent_2='Updates saved successfully ';
                        }
                        else{
                            $this->panelContent_2='No updates have been saved - no changes were detected to record data.';
                        }
                        
                        break;
                    case 'savePasswordChange':
                        //get the values entered in the form
                        $pass1=$this->db->real_escape_string($this->postArray['newPass1']);   
                        $pass2=$this->db->real_escape_string($this->postArray['newPass2']);
                        $oldPass=$this->db->real_escape_string($this->postArray['oldPass']);
                        
                        //fopllow the procedure to change the password
                        if($pass1===$pass2){ //check new passwords entered in the form are the same
                            
                            if($this->user->verifyPassword($oldPass)){//verify old password correct
                                
                                if($this->user->changePassword($pass1)){ //try to change the password
                                $this->panelContent_2.='Password Changed Successfully - use the new password next time you log in. ';
                                }
                                else{//somwething else went wrong!
                                    $this->panelContent_2.='Password has not been changed. A database error has occurred - contact the administrator';
                                }
                            }
                            else { //old password is not correct
                                $this->panelContent_2.='Password NOT Changed - Your old password has not been verified - please try again: '.$this->user->getErrMsg();
                            }
                        }
                        else{ //new passwords entered in form dont match
                            $this->panelContent_2.='Password NOT Changed - new passwords entered must match - please try again' ;
                        }
                        break;
                    default:
                        $this->panelContent_2.='Invalid Choice';
                        break;
                }    
            }
            else{  //the button has not been pressed
                $this->panelContent_2='Please enter details of required changes in the form';
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
        public function getUser(){return $this->user;}
        //END GETTER METHODS

        
}//end class
        