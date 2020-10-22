<?php
ini_set('session.gc_maxlifetime',  120);
//ini_set('session.use_only_cookies',1);
ini_set('session.cookie_lifetime',0);
session_start();
$_COOKIE['LAST_ACTIVITY'] = time();
$path = 'http://localhost/htdocs/eclipse_workspace/esame';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
if (isset($_COOKIE['id'])) {
    header('Location: welcome.php');
    die();
}
require_once 'databaseFunctions.php';
//dropDB();
createDatabase();
require_once 'sits.php';
//showUsers();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>main page</title> 
        <script type="text/javascript" src="jquery-1.7.2.js"></script>
        <noscript>Sorry, but Javascript is required</noscript>
        <script>
        	if(!navigator.cookieEnabled) window.location.href('nocookies.php');
        </script>
        <script type="text/javascript" src="main_page.js"></script>
        <script type="text/javascript" src="commonFunctions.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="mystyle.css" rel="stylesheet" />
       
    </head>
    <body>
    <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 rigaaa opacity_medium">
    <header class="row intestazione" >
    	<div class="col-xs-12 col-xs-offset-0 col-md-12 col-md-offset-0"><h1><b>Polito</b></h1>
    	<h1 style="display: inline;"><img src="images/World_Travel.jpg" class="icon" style="display: inline;"></img>
    	<b>Airlines</b> </h1></div>
    </header>
    <div class="row"><div class="col-xs-12 col-md-12 text-center"><h2>Travels all over the world</h2> </div></div>
        <div class="row riga">
        
        <div class="col-xs-10 col-xs-offset-1 col-md-4 col-md-offset-0 primariga">
         	<form id="loginform" method="post" >
         		<h3>Enter in Polito Airlines member area</h3>
        		E-Mail: <input class="mail" type="text" id="login_email" placeholder="example: a@polito.it" autocomplete="on"/><br/>
        		Password: <input class="typepass" type="password" id="login_password" placeholder="****" autocomplete="on"/><br/>
        		<input id='login_checkbox' type="checkbox" /> <b>Show Password</b> 
        		<input class="btn" type="submit" value="Log in" id="loginbtn"/>
            </form>
        
        <div id="not_already_registered">
            <p>Not already registered? Register now!</p>
            <input class="btn" type="button" value="Sign up" id="signupbtn" style="display:"/>
        </div>
        <div id="signupform" style="display:none;">
         	<form method="post">
         		<h3> Create your Polito Airlines Account</h3>
        		E-Mail: <input class="mail" type="text" id="signup_email" placeholder="Email Address" autocomplete="on"/><br/>
        		Password: <input class="typepass" type="password" id="signup_password" placeholder="Password" autocomplete="on"/><br/>
        		<input id='signup_checkbox' type="checkbox" /> <b>Show Password</b> 
        		<input class="btn" type="submit" value="create Account" id="signupconfirmbtn"/>        		
            </form>
        </div>
        <div id="already_registered" style="display:none;">
            <p>Already registered? Login now!</p>
            <input class="btn" type="button" value="Log in" id="loginformbtn" style="display:"/>
        </div>
        </div>
        
        <?php 
        displaySits();
        ?></div></div>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>

