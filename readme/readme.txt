This PHP application is provided as an MVC template for term assignment A01. 

To setup - follow normal procedures that we have followed in class 
-unzip the code to you htdocs folder
-restore the database contained in the /database folder

This application provides controllers for 3 types of user 
-User not logged in
-Administrator
-Customer

The database contains only one table - user - you will have to add your own tables to build your own application. 

There are 2 users registered with the following username/passwords that you can try out: 

Administrator: 
  Username: admin@lit.ie
  Password: Password_1234  
Customer: 
  Username: k00999999@lit.ie
  Password: Password_1234 

Login and user registration processes are provided as working. 

Registration Function
---------------------
1. Users must use their email address to register. The registration process checks that the users email is not already registered. 
2. All users are set to ENABLED and CUSTOMER type by default at registration. 
3. When the user attribute 'enabled' is set to 0 in the user table - that user will not be able to login. 
4. When the user attribute 'administrator' is set to 1 in the user table - that user will be able to login as an administrator. 

Login Function
-----------------
Users enter their email and password in the login form.  The login process checks 
1. That the values entered are valid 
2. The user is ENABLED
3. The type of user ADMINISTRATOR or CUSTOMER



  
  