<?php
$path = 'http://localhost/htdocs/eclipse_workspace/esame';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'databaseFunctions.php';

function displaySits(){

if(!isset($_COOKIE['id'])){
    $table_options='class="col-md-4 col-xs-10 col-xs-offset-1 col-md-offset-0 text-center tabella"';
    $p_options='class="col-md-3 col-md-offset-0 col-xs-12 col-xs-offset-0 sottotabella"';
}
else{
    $table_options='class="col-md-4 col-xs-10 col-xs-offset-1 col-md-offset-1 text-center"';
    $p_options='class="col-md-3 col-md-offset-0 col-xs-12 col-xs-offset-0 sottotabella"';
}
//$p_options='class="col-md-3 col-md-offset-0 col-xs-4 col-xs-offset-4 sottotabella"';
$td_option="elementotabella";

echo "<div $table_options><table class='col-md-12 col-xs-12 tabella'>";
if(!isset($_COOKIE['id'])){
    echo "<p class='text-center'><b> Click on sits to check their avalability </p>";
}
else{
    echo "<p class='text-center'><b> Book your sits by clicking them! </p>";
}
$max_row=getSitsRow();
$max_col=getSitsCol();
$alphabet = range('A', 'Z');
for($row=0;$row<=$max_row;$row++){
    for($col=0;$col<=$max_col;$col++){
        if($row==0){
            if($col==0) echo "<tr><th> posti </th>";
            else if($col==$max_col) echo "<th>Posto ".$alphabet[$col-1]."</th></tr>";
            else echo "<th>Posto ".$alphabet[$col-1]."</th>";
        }
        else {
            $query="SELECT status
                    FROM MySits
                    WHERE col='$col' AND row='$row'";
            $status=performQuery($query, 'status');
            if($status=='booked')
            {
                $query="SELECT user
                FROM MySits
                WHERE col='$col' AND row='$row' AND status='booked'";
                $user=performQuery($query, 'status');
                if(isset($_COOKIE['id'])&&$user==$_COOKIE['id']){
                    $status='myBook';
                }
            }
            
            if($col==$max_col) echo "<td id='td-$row-$col' class='$status $td_option'>".$alphabet[$col-1].$row."</td></tr>";
            else if($col==0) echo "<tr><th>Fila ".$row."</th>";
            else echo "<td id='td-$row-$col' class='$status $td_option'>".$alphabet[$col-1].$row."</td>";
        }
    }
}

echo "</table>";
if(isset($_COOKIE['id'])){
    echo '<div class="row">';
    echo '<div class="col-md-12 col-xs-12 text-center">
           <input class="btn" type="button" value="Buy now!" id="buybtn" style="display:none"/>
          </div></div>';
}

echo "</div><div $p_options>";
echo "<div class='row primorettangolo'>";

echo "<div class=' col-md-1 col-md-offset-0 col-xs-1 col-xs-offset-4 rettangolo'></div><div class='col-md-11 col-xs-4'> Total sits: ".getSitsNumber()."</div></div>";
echo "<div class='row primorettangolo'>";
echo "<div class='col-md-1 col-md-offset-0 col-xs-1 col-xs-offset-4 rettangolo free'></div><div class='col-md-11 col-xs-4'> free : ".getSitsNumber("free")."</div></div>";
echo "<div class='row primorettangolo'>";
echo "<div class='col-md-1 col-md-offset-0 col-xs-1 col-xs-offset-4 rettangolo booked'></div><div class='col-md-11 col-xs-4'> booked : ".getSitsNumber("booked")."</div></div>";
echo "<div class='row primorettangolo'>";
echo "<div class='col-md-1 col-md-offset-0 col-xs-1 col-xs-offset-4 rettangolo bought'></div><div class='col-md-11 col-xs-4'> bought : ".getSitsNumber("bought")."</div></div>";
if(isset($_COOKIE['id'])){
    echo "<div class='row primorettangolo'>";
    echo "<div class='col-md-1 col-md-offset-0 col-xs-1 col-xs-offset-4 rettangolo myBook'></div><div class='col-md-5 col-xs-4'> my Sits booked:</div>"."<div class='numero col-md-1' id='my_sits_booked'>".getSitsNumber('booked',$_COOKIE['id'])."</div></div>";
    echo "<div class='row primorettangolo'>";
    echo "<div class='col-md-1 col-md-offset-0 col-xs-1 col-xs-offset-4 rettangolo bought'></div><div class='col-md-5 col-xs-4'> my Sits bought: </div> "."<div  class=' numero col-md-1' id='my_sits_bought'>".getSitsNumber('bought',$_COOKIE['id'])."</div></div>";
}
echo "<div class='row '>";
echo "<div class='col-md-1 col-md-offset-0 col-xs-11 col-xs-offset-0 text-center'><input class='btn updatepad' type='button' value='update' id='refreshbutton'/></div></div>";
echo "</div>";
}

?>