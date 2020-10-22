<?php

$path = 'http://localhost/htdocs/eclipse_workspace/esame';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'securityFunctions.php';
require_once 'userSession.php';
require_once 'databaseFunctions.php';
require_once 'sits.php';

if(isset($_POST['action'])) {
    $action=$_POST['action'];
    $_COOKIE['LAST_ACTIVITY']=time();
    if(isset($_COOKIE['id'])){
        setcookie("id",$_COOKIE['id'],time()+120,1);
    }
    
    if(!empty($action)){
        
        switch($action) {
            
            case 'checkSitStatus':
                if(isset($_POST['id'])) {
                    $cid=$_POST['id'];
                    if(!empty($cid))
                        echo json_encode(checkSitStatus(getCoord($cid)[1], getCoord($cid)[2]));
                }
                break;
                
            case 'reserve':
                if(isset($_POST['id'])&&isset($_COOKIE['id'])) {
                    $cid=$_POST['id'];
                    $uid=$_COOKIE['id'];
                    if(!empty($cid))
                        echo json_encode(reserve(getCoord($cid)[1], getCoord($cid)[2],$uid));
                    else echo json_encode(array("booking"=>"fail - empty id"));
                }
                else echo json_encode(array("booking"=>"fail - user id not found, please relog"));
                break;
                
            case 'buySits':
                if(isset($_POST['count'])&&isset($_COOKIE['id'])) {
                    $uid=$_COOKIE['id'];
                    $number=$_POST['count'];
                    if(!empty($number))
                        echo json_encode(buySits($number,$uid));
                    else echo json_encode(array("buying"=>"fail - number is empty"));
                }
                else echo json_encode(array("buying"=>"fail - user id not found, please relog"));
                break;
                
            case 'reloadSits':
                echo json_encode(reloadSits());
                break;
                
            case 'getMySits': 
                if(isset($_COOKIE['id']))
                    echo json_encode(getMySits($_COOKIE['id']));
                else echo json_encode(array("buying"=>"fail - user id not found, please relog"));
                break;
           
            default: break;
        }
    }
    else echo "<script type='text/javascript'>alert('$action is empty');</script>";
}

if(isset($_POST['userAction'])) {
    $userAction=$_POST['userAction'];
    $_COOKIE['LAST_ACTIVITY']=time();
    if(isset($_COOKIE['id'])){
        setcookie("id",$_COOKIE['id'],time()+120,1);
    }
    
    if(!empty($userAction)){
        
        switch($userAction){
            
            case 'checkAccount':
                if(isset($_POST['id'])&&isset($_POST['pwd'])) {
                    $id=$_POST['id'];
                    $pwd=$_POST['pwd'];
                    
                    if(!empty($id)&&!empty($pwd))
                        echo json_encode(array("checkOut"=>checkAccount($id, $pwd)));
                }
                break;
                
            case 'createAccount':
                
                if(isset($_POST['id'])&&isset($_POST['pwd'])) {
                    $id=$_POST['id'];
                    $pwd=$_POST['pwd'];
                    if(!empty($id)&&!empty($pwd)){
                        echo json_encode(array("signup"=>createAccount($id, $pwd)));
                    }
                }
                break;
                
            case 'refresh':
                header("Location: main_page.php"); die(); break;
           
            
            
            case 'logOut': 
                if(isset($_COOKIE['id'])){
                    echo json_encode(array("logOut"=>logOut()));
                }
                else
                    echo json_encode(array("logOut"=>"fail - already logged out"));
                break;
                
            
            default: break;
        }
    }
}



?>