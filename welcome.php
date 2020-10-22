<?php 
ini_set('session.gc_maxlifetime',  120);
//ini_set('session.use_only_cookies',1);
ini_set('session.cookie_lifetime',0);
session_start();
$_COOKIE['LAST_ACTIVITY'] = time();
$path = 'http://localhost/htdocs/eclipse_workspace/esame';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
validSession();
if (!isset($_COOKIE['id'])) {
    header('Location: main_page.php');
    die();
}
require_once 'databaseFunctions.php';
require_once 'sits.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>welcome page</title> 
        <script type="text/javascript" src="jquery-1.7.2.js"></script>
        <noscript>Sorry, but Javascript is required</noscript>
        <script>
        	if(!navigator.cookieEnabled) window.location.href('nocookies.php');
        </script>
        <script type="text/javascript" src="welcome.js"></script>
        <script type="text/javascript" src="commonFunctions.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="mystyle.css" rel="stylesheet" />
    </head>
    <body>
    <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 rigaaa">
    <header class="row intestazione" >
    	<div class="col-xs-12 col-xs-offset-0 col-md-12 col-md-offset-0"><h1><b>Polito</b></h1>
    	<h1 style="display: inline;"><img src="images/World_Travel.jpg" class="icon" style="display: inline;"></img>
    	<b>Airlines</b> </h1></div>
    </header>
	<div class="row"><div class="col-xs-12 col-md-12 text-center"><h2>Welcome <?php echo explode("@",$_COOKIE['id'])[0];?></h2></div></div>
	 <div class="row riga">
	    <div class="col-xs-10 col-xs-offset-1 col-md-2 col-md-offset-1 logout">
            <div>
              <input class="btn" type="button" value="Log out" id="logoutbtn" style="display:"/>
            </div>
        </div>
        <div class="row blabla">
        <?php 
        displaySits();
        ?>
        </div>         
        </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
<?php 

function validSession(){
    if (isset($_COOKIE['LAST_ACTIVITY']) && (time() - $_COOKIE['LAST_ACTIVITY'] > 120)) {
        session_unset();     // unset $_COOKIE variable for the run-time
        session_destroy();
        if(isset($_COOKIE['id']))
            setcookie("id","",time() - 3600);
        header('Location main_page.php');
        die();
        //redirect("session expired");
    }
    $_COOKIE['LAST_ACTIVITY'] = time(); // update last activity time stamp
    if (!isset($_COOKIE['CREATED'])) {
        $_COOKIE['CREATED'] = time();
    } else if (time() - $_COOKIE['CREATED'] > 120) {
        session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
        $_COOKIE['CREATED'] = time();  // update creation time
    }
}?>
