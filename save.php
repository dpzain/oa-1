<?php
$result=$_POST['data'];
$result=serialize($result);

$host = 'localhost';
$username = 'root';
$password = 'root';
$con = mysql_connect($host, $username, $password);
mysql_select_db('oa');
$sql = 'set names utf8';
mysql_query($sql);
$sql = "update work set content='$result' where id='5'";
if (!mysql_query($sql)) {
    echo 'error!';
}else{
    echo 'success!';
}
mysql_close($con);
?>