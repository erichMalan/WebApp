<?php

function secure($retrieve){
    if(!empty($retrieve)){
        $msg = trim(htmlentities($retrieve));
        return $msg;
    }
    else{
        return "";
    }
}

function ensure($retrieve=""){
    if(validParameter($retrieve)){
        $validPassword = !empty($retrieve) ? 
                         ctype_alnum($retrieve) ? 
                         strcspn($retrieve, 'abcdefghijklmnopqrstuvwxyz') != strlen($retrieve) ?  
                         (strcspn($retrieve, '0123456789') != strlen($retrieve) || strcspn($retrieve, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ') != strlen($retrieve)) ?
                         "ok" :
                         "<br />No capital letter or number" : "<br />No small letter" : "<br />retrieveword has special character" : "<br />retrieveword field is empty";
        if($validPassword=="ok"){
            $hash = password_hash(trim($retrieve), PASSWORD_DEFAULT,array("cost" => 10));
            $hash = substr( $hash, 0, 60 );
            return $hash;
        }
        //echo "<script type='text/javascript'>alert('$validPassword');</script>";
        return "";
    }
    return "";
}

function validEmail($email){
    if (empty($email)) {
        return "";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            return "";
        else return "valid";
    }

}

function validParameter($value){
    if(!empty($value))
        return trim($value);
    //echo "<script type='text/javascript'>alert('$param is empty. value: $value');</script>";
    return "";
}

function SanitizeString($var) {
    $var = strip_tags($var);
    $var = htmlentities($var);
    return stripslashes($var);
}

?>