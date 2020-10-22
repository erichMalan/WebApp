<?php
$path = 'http://localhost/htdocs/eclipse_workspace/esame';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'securityFunctions.php';
require_once 'userSession.php';
    
function dropDB(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql="DROP DATABASE IF EXISTS myDB;";
    if ($conn->query($sql) === FALSE) {
        echo "Error creating database: " . $conn->error. "<br>";
    }
    createDatabase();
}/*
$sql="SELECT SCHEMA_NAME
FROM INFORMATION_SCHEMA.SCHEMATA
WHERE SCHEMA_NAME = 'myDB'";*/

function createDatabase(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS myDB;";
    if ($conn->query($sql) === FALSE) {
        echo "Error creating database: " . $conn->error. "<br>";
    }
    $conn->close();
    setUp();
}


function createGuestsTable(){
    $sql = "CREATE TABLE IF NOT EXISTS MyGuests (
    		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		email VARCHAR(30) NOT NULL,
    		password VARCHAR(255) NOT NULL,
    		reg_date TIMESTAMP
    		)";
    
    performQuery($sql);
    setUpGuests();
}


function createSitsTable(){
    $sql = "CREATE TABLE IF NOT EXISTS MySits (
    		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            row INT(6),
            col INT(6),
            status VARCHAR(20),
            user VARCHAR(20),
    		reg_date TIMESTAMP
    		);";
    
    performQuery($sql);
    $sit_rows=10;
    $sit_col=6;
    for($row_counter=1;$row_counter<=$sit_rows;$row_counter++){
        for($col_counter=1;$col_counter<=$sit_col;$col_counter++){
            $sql = "INSERT INTO MySits(row,col,status,user) VALUES('$row_counter','$col_counter','free',NULL);";
            performQuery($sql);
        }
    }
}


function setUpGuests(){
    createAccount('u1@p.it', 'p1');
    //logOut('u1@p.it');
    createAccount('u2@p.it', 'p2');
    //logOut('u2@p.it');
}

function setUp(){
    try{
        performQuery('select 1 from MyGuests limit 1','exist');
    }catch(Exception $e){
        createGuestsTable();
    }
    try{
        performQuery('select 1 from MySits limit 1','exist');
    }catch(Exception $e){
        createSitsTable();
    }
}


function getSitsNumber($status="",$id=""){
    if(empty($id)){
        if(empty($status)){
            $query="SELECT COUNT(id) AS sits_number FROM MySits;";
            $result=performQuery($query, 'sits_number');
            return $result;
        }
        else{
            $query="SELECT COUNT(id) AS sits_number FROM MySits WHERE status='$status';";
            $result=performQuery($query, 'sits_number');
            return $result;
        }
    }
    else{
        $query="SELECT COUNT(id) AS sits_number FROM MySits WHERE status='$status' AND user='$id';";
        $result=performQuery($query, 'sits_number');
        return $result;
    }
}

function getSitsRow(){
    $query="SELECT MAX(row) AS max_row FROM MySits WHERE col=1;";
    $result=performQuery($query, 'max_row');
    return $result;
}

function getSitsCol(){
    $query="SELECT MAX(col) AS max_col FROM MySits WHERE row=1;";
    $result=performQuery($query, 'max_col');
    return $result;
}

function checkSitStatus($row,$col){
    $query="SELECT status 
            FROM MySits
            WHERE row='$row' AND col='$col';";
    $result=performQuery($query, 'status');
    return $result;
}

function reserve($sit_row,$sit_col,$user){

    $performed="SELECT user
        FROM MySits
        WHERE row='$sit_row' AND col='$sit_col' AND status='booked';";
    $performed=performQuery($performed, 'user');
    if($performed==$user)
    {
        $query="UPDATE MySits
           SET Status = 'free', user = null
           WHERE row='$sit_row' AND col='$sit_col'
           AND status='booked' AND user='$user';";
        performQuery($query);
        return array("unbook"=>"success - sit now is ".checkSitStatus($sit_row, $sit_col));
    }
        
    else if(checkSitStatus($sit_row, $sit_col)!='bought')
    {
        $query="UPDATE MySits
               SET Status = 'booked', user = '$user'
               WHERE row='$sit_row' AND col='$sit_col'
               AND status<>'bought';";
        performQuery($query);
        $performed="SELECT user 
            FROM MySits
            WHERE row='$sit_row' AND col='$sit_col' AND status='booked' AND user='$user';";
        $result=performQuery($performed, 'user');
        if($result==$user) return array("reservation"=>"success - sit booked");
    }
    return array("reservation"=>"fail - sit" . $sit_row."x"."$sit_col". "has been already taken, please choose another sit");  
}


function refreshSits($user){
    $query="UPDATE MySits
               SET status = 'free', user = NULL
               WHERE user='$user'
               AND status='booked';";
    performQuery($query);
    return;
}

function buySits($sits_count,$user){
    $already_bought=checkBuySits($user,'bought');
    $sits=checkBuySits($user,'booked');
    $diff=$sits_count-$sits;
    if($diff!=0){
        refreshSits($user);
        return array("fail"=>"$diff of $sits_count sits had already been bought");
    }
        
    $query="UPDATE MySits
               SET status = 'bought'
               WHERE user='$user'
               AND status='booked';";
    performQuery($query);
    $sits=checkBuySits($user,'bought');
    $diff=$already_bought+$sits_count-$sits;
    if($diff!=0){
        refreshSits($user);
        return array("fail"=>"$diff sits had already been bought while processing the request");
    }
    else {
        return array("success"=>"you successfully bought $sits_count sits");
    }
}

function checkBuySits($user,$status){
    $query="SELECT COUNT(id) AS _SITS
                FROM MySits
                WHERE user='$user' AND status='$status';";
    $sits=performQuery($query,'_SITS');
    return $sits;
}

function getMySits($user){
    $query="SELECT COUNT(id) as  _number
                FROM MySits
                WHERE user='$user' AND status='booked';";
    $sitsbooked=performQuery($query);
    $query="SELECT COUNT(id) as  _number
                FROM MySits
                WHERE user='$user' AND status='bought';";
    $sitsbought=performQuery($query);
    $sits=$sitsbooked." | ".$sitsbought;
    return array("mysits"=>$sits);
}

function reloadSits(){
    $query="SELECT row,col,status,user
        FROM MySits;";
    $result=performQueryMKArray($query);
    $result=extractIDs($result);
    return array("sits reloading"=>$result);
}

function extractIDs($array){
    $str="";
    foreach($array as $r){
        foreach($r as $key=>$value){
            if($key=='user'&&isset($_COOKIE['id'])){
                if($value==$_COOKIE['id']){
                    $str=$str.$key."=>".$value."||";
                }
                else{
                    $str=$str.$key."=>other||";
                }
            }
            else if($key=='user'&&!isset($_COOKIE['id'])){
                $str=$str.$key."=>other||";
            }
            $str=$str.$key."=>".$value."||";
        }
        $str=$str."<br/>";
    }
    return $str;
}

function getCoord($cid){
    $coord = explode("-", $cid);
    return $coord;
}


function performQuery($query="",$key=""){
    $mysqli=dbConnect();
    if(empty($query)){
        $stmt = $mysqli->prepare($query);
        $stmt->execute();
        return;
    }
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows === 0) return"";
    $stmt->bind_result($key);
    $str="";
    while($stmt->fetch()){
        $str=$str.$key;
    }
    $stmt->close();
    $mysqli->close();
    return $str;
}

function performQueryMK($query=""){
    $mysqli=dbConnect();
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    // get column names for binding return results
    $resultmeta = mysqli_stmt_result_metadata($stmt);
    list($columns, $columns_vars) = array(array(), array());
    while ( $field = mysqli_fetch_field($resultmeta) ) {
        $columns[] = $field->name;
        $columns_vars[] = &${'column_' . $field->name};
    }
    // call bind function with arguments in array
    call_user_func_array('mysqli_stmt_bind_result', array_merge(array($stmt), $columns_vars));
    // get return results
    $return_array = array();
    while ( mysqli_stmt_fetch($stmt) ) {
        $row = array();
        foreach ( $columns as $col ) {
            $row[$col] = ${'column_' . $col}; // populate assoc. array with data
        }
        $return_array[] = $row; // push row data onto return array
    }
    $stmt->close();
    $mysqli->close();
    $str="";
    foreach($return_array as $r){
        foreach($r as $key=>$value){
            $str=$str.$key."=>".$value."||";
        }
        $str=$str."<br/>";
    }
    return $str;
}

function performQueryMKArray($query=""){
    $mysqli=dbConnect();
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    // get column names for binding return results
    $resultmeta = mysqli_stmt_result_metadata($stmt);
    list($columns, $columns_vars) = array(array(), array());
    while ( $field = mysqli_fetch_field($resultmeta) ) {
        $columns[] = $field->name;
        $columns_vars[] = &${'column_' . $field->name};
    }
    // call bind function with arguments in array
    call_user_func_array('mysqli_stmt_bind_result', array_merge(array($stmt), $columns_vars));
    // get return results
    $return_array = array();
    while ( mysqli_stmt_fetch($stmt) ) {
        $row = array();
        foreach ( $columns as $col ) {
            $row[$col] = ${'column_' . $col}; // populate assoc. array with data
        }
        $return_array[] = $row; // push row data onto return array
    }
    $stmt->close();
    $mysqli->close();
    return $return_array;
}

function performQueryResultArrayToString($query,$key){
    $mysqli=dbConnect();
    $arr = [];
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $arr[] = $row;
    }
    if(!$arr) exit('No rows');
    $result=json_encode($arr);
    $stmt->close();
    $mysqli->close();
    $resUser=json_decode($result);
    $str="";
    foreach($resUser as $res){
        $str=$str.($res->$key)." ";
    }
    return $str;
}


function dbConnect(){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $mysqli = new mysqli("localhost", "root", "", "myDB");
        $mysqli->set_charset("utf8mb4");
        return $mysqli;
    } catch(Exception $e) {
        exit('Error connecting to database'.$e->getMessage());
    }
}


function showUsers(){
    $query="SELECT password from MyGuests";
    $str=performQueryResultArrayToString($query, 'password');
    echo "<script type='text/javascript'>alert('$str');</script>";
}



?>