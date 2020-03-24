<?php
/**
 * Class: User
 * 
 * The user class represents the end user of the application. 
 * 
 * This class is responsible for providing the following functions:
 * 
     * User registration
     * User Login
     * User Logout
     * Persisting user session data by keeping the$_SESSION array up to date
 *
 * @author gerry.guinane
 */
class User extends Model {
    
//CLASS properties
    private $session;       //Session Class
    private $db;            //MySQLi object: the database connection ( 
    private $userID;        //String: containing User ID
    private $userFirstName; //String: 
    private $userLastName;  //String: 
    private $userType;      //String: usertype is either LECTURER or STUDENT
    private $postArray;     //Array - copy of $_POST array
    private $chatEnabled;   //boolean: TRUE if AJAX chat is enabled for this session
    private $errMsg;        //string containing an error message 

//CLASS methods	

    //METHOD: constructor 
    function __construct($session,$database) {   
        parent::__construct($session->getLoggedinState());
        $this->db=$database;
        $this->session=$session;
        //get properties from the session object
        $this->userID=$session->getUserID();
        $this->userFirstName=$session->getUserFirstName();
        $this->userLastName=$session->getUserLastName();
        $this->userType=$session->getUserType();
        $this->chatEnabled=$session->getChatEnabledState();
        $this->postArray=array();
        $this->errorMsg='No error message';
    }
    //END METHOD: constructor 

    //METHOD: login($userID, $password)
    public function login($email, $password) {
        //This login function checks both the student and lecturer tables for valid login credentials

        //encrypt the password
        $password = hash('ripemd160', $password);
        
        //set up the SQL query strings
        $sql="SELECT FirstName,LastName,id, enabled,administrator FROM user WHERE email='$email' AND PassWord='$password'";

        //execute the query
        $rs1=$this->db->query($sql); //query the lecturer table


        //use the resultset to determine if login is valid and which type of user has logged on. 
        if(($rs1->num_rows===1)){  //only one row returned if email and password supplied are correct
            
            $row=$rs1->fetch_assoc(); //get the users record from the query result  
            
            //set the session and class member variable values
            $this->session->setUserID($row['id']);
            $this->session->setUserFirstName($row['FirstName']);
            $this->session->setUserLastName($row['LastName']);
            $this->userID=$row['id'];
            $this->userFirstName=$row['FirstName'];
            $this->userLastName=$row['LastName'];
                
               

            //check if user is administrator
            if($row['administrator']){
                $this->userType='ADMINISTRATOR';
                $this->session->setUserType('ADMINISTRATOR');                     
            }
            else{
                $this->userType='CUSTOMER';
                $this->session->setUserType('CUSTOMER');                     
            }

            //check if user is enabled
            if($row['enabled']){
                $this->session->setLoggedinState(TRUE);
                $this->loggedin=TRUE;
                return TRUE;
            }
            else{  //user has not been enabled
                $this->errMsg.='Unable to complete login -User account is not enabled, please contact administrator.';
                $this->session->setLoggedinState(FALSE);
                $this->loggedin=FALSE;
                return false;
            }    
        }
        else{ //invalid login credentials entered 
            $this->session->setLoggedinState(FALSE);
            $this->loggedin=FALSE;
            $this->errMsg.='Unable to complete login - Incorrect email or password provided. ';
            return FALSE;
        }

        //close the resultsets
        $rs1->close();
    }
    //END METHOD: login($userID, $password)

    //METHOD: logout()
    public function logout(){
        //
        $this->session->logout();
    }
    //END METHOD: logout()

    //METHOD: register($postArray)
    public function register($postArray){
        //get the values entered in the registration form
        $email=$this->db->real_escape_string($postArray['email']);
        $firstName=$this->db->real_escape_string($postArray['FirstName']);
        $lastName=$this->db->real_escape_string($postArray['LastName']);
        $password=$this->db->real_escape_string($postArray['Pass1']);
        
        //check user's email is not already registered
        $sql="SELECT * FROM USER WHERE email='$email'";
        $rs=$this->db->query($sql);
        
        if ($rs->num_rows==0){  //user has not registered this email previously
            //encrypt the password
            $password = hash('ripemd160', $password);
            //construct the INSERT SQL
            $sql="INSERT INTO USER (email,FirstName,LastName,PassWord) VALUES ('$email','$firstName','$lastName','$password')";

            //$sql="INSERT INTO lecturer (LectID,FirstName,LastName,PassWord) VALUES ('".$postArray['lectID']."','".$postArray['lectFirstName']."','".$postArray['lectLastName']."','".$postArray['lectPass1']."')";
            //execute the insert query
            $rs=$this->db->query($sql); 
            
            //check the insert query worked
            if ($rs){return TRUE;}else{$this->errMsg='Unable to register new user - please contact site administrator'; return FALSE;}            
        }
        else {  //email has previously been used to register user
            $this->errMsg='Email address is already registered';
            return FALSE;
         }

    }
    //END METHOD: register($postArray)

    
    //helper methods
    
    //METHOD: recordEditForm()
    public function recordEditForm(){
        //this helper method generates a record edit FORM as a string
        //the form is populated with the currently logged in user's details for editing
        //the UserID is not editable (read only)
        //this method produces a different form depending on the type of user that is logged in (LECTURER or STUDENT)
        $returnString='';
 
        //this method generates a record edit form
        $sql="SELECT id,FirstName,LastName,email,mobile FROM user WHERE id='".$this->getUserID()."'";

        if((@$rs=$this->db->query($sql))&&($rs->num_rows===1)){  //execute the query and check it worked and returned data    
                //use the resultset to create the EDIT form
                $row=$rs->fetch_assoc();
                //construct the EDIT ACCOUNT DETAILS form 
                $returnString.='<form method="post" action="index.php?pageID=accountEdit">';
                $returnString.='<div class="form-group">';
                $returnString.='<label for="id">User ID</label><input required readonly type="text" class="form-control" value="'.$row['id'].'" id="LecturerID" name="User ID"  title="This field cannot be edited">';
                $returnString.='<label for="FirstName">FirstName</label><input required type="text" class="form-control" value="'.$row['FirstName'].'" id="FirstName" name="FirstName" pattern="[a-zA-Z0-9óáéí\' ]{1,45}" title="FirstName (up to 45 Characters)">';
                $returnString.='<label for="LastName">LastName</label><input required type="text" class="form-control" value="'.$row['LastName'].'" id="LastName" name="LastName" pattern="[a-zA-Z0-9óáéí\' ]{1,45}" title="LastName (up to 45 Characters)">';
                $returnString.='<label for="email">email</label><input required readonly type="text" class="form-control" value="'.$row['email'].'" id="email" name="email" title="This field cannot be edited">';
                $returnString.='<label for="mobile">mobile</label><input required type="text" class="form-control" value="'.$row['mobile'].'" id="mobile" name="mobile" pattern="[0-9()- ]{1,45}" title="mobile (up to 45 Characters)">';

                $returnString.='</div>';
                $returnString.='<button type="submit" class="btn btn-default" name="btn" value="accountSave">Save Changes</button>';
                $returnString.='</form>'; 
        }
            else{
                $returnString.='Invalid selection - Contact Administrator';
            }
            
        

        return $returnString;
    }
    //END METHOD: recordEditForm()
    
    //METHOD: saveUpdate($sql)
    public function saveUpdate($sql){
        //this method executes the $sql argument and verifies only 1 record/row affected
        if((@$rs=$this->db->query($sql)===TRUE)&&($this->db->affected_rows===1)){ 
            return TRUE;
        }
        else{
            return FALSE;
        }        
    }
    //END METHOD: saveUpdate($sql)
    
    //METHOD: verifyPassword($password)
    public function verifyPassword($password){
        //This method verifies a password for a user that is logged in

        //encrypt the password
        $password = hash('ripemd160', $password);
        
        $sql ="SELECT * FROM user WHERE id='$this->userID' AND PassWord='$password'";

        
        //execute the query and verify the result
        if( (@$rs=$this->db->query($sql))&&($rs->num_rows===1)){ //only one record should be returned if the password is valid
            return TRUE;  //password is valid for the current user
        }
        else{
            $this->errMsg.='Incorrect Password: '.$sql;
            return FALSE; //password is NOT valid for the current user
        }  
        
    }
    //END METHOD: verifyPassword($password)
      
    //METHOD: changePassword($password)
    public function changePassword($password){
        //This method changes the users password 
        
        //encrypt the password passes as argument
        $password = hash('ripemd160', $password);
        
        $sql="UPDATE user SET password = '$password'  WHERE id = '$this->userID'"; 
        
        //execute the query and verify only 1 row has been affected
        if((@$rs=$this->db->query($sql)===TRUE)&&($this->db->affected_rows===1)){ 
            return $sql;
        }
        else{
            return $sql;
        }  
        
    }  
    //END METHOD: changePassword($password)
        
    //setters
    public function setLoginAttempts($num){$this->session->setLoginAttempts($num);}
    public function setChatEnabledState($state){$this->session->setChatEnabledState($state);}
    
    //getters
    public function getLoggedInState(){return $this->session->getLoggedinState();}//end METHOD - getLoggedInState        
    public function getUserID(){return $this->userID;}
    public function getUserFirstName(){return $this->userFirstName;}
    public function getUserLastName(){return $this->userLastName;}
    public function getUserType(){return $this->userType;}
    public function getLoginAttempts(){return $this->session->getLoginAttempts();}  
    public function getChatEnabledState(){return $this->chatEnabled;}
    public function getErrMsg(){return $this->errMsg;}
}
