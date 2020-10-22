<?php

$path = 'http://localhost/htdocs/eclipse_workspace/esame';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'databaseFunctions.php';
require_once 'securityFunctions.php';

function checkAccount($username,$password){
    $username=secure($username);
    $password=secure($password);
    $msg="";
    if(empty($username)||empty(validEmail($username)))
        $msg="fail - email format is not valid"; 
    else if(empty($password))
        $msg="fail - password format is not valid";
    if(!empty($msg))
        return $msg;
    
    $query="SELECT password
            FROM MyGuests
            WHERE email='$username';";
    $result=performQuery($query, 'password');
    if(!empty($result)){
        if(password_verify($password,$result)){
            $msg="success - ".logIn($username);
        }
        else $msg= "fail - invalid password";
    }
    
    else $msg= "fail - email not found";
    return $msg;
}

function createAccount($user,$password){
    $msg="";
    if(empty($user=secure($user))||empty(validEmail($user)))
        $msg="fail - email format is not valid";
    else if(empty(secure($password)))
        $msg = "fail - password format is not valid";
    else if(alreadyPresent("email",$user)) 
        $msg = "fail - email is already registered";
    if(!empty($msg)){
        return $msg;
    }
    $hash_password=ensure($password);
    $sql = "INSERT INTO MyGuests(email,password) VALUES ('$user','$hash_password');";
    performQuery($sql);
    return checkAccount($user,$password);
}

function alreadyPresent($type,$dataToCheck){
    $res ="SELECT COUNT(*) AS _found FROM MyGuests WHERE $type='$dataToCheck';";
    if(performQuery($res, '_found')>0){
        return true;
    }
    return false;
}


function logIn($user){
    setcookie("id",$user,time()+120,1);
    return $user;
}


function logOut(){
    if(isset($_COOKIE['id'])){
        setcookie("id","",time() - 3600,1);
        $msg="success - log out successful";
    }
    else 
        $msg="fail - already logged out";
    return $msg;
}

?>