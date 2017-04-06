<?php
$host = 'localhost';
$username='root';
$password='root';
$con=mysql_connect($host,$username,$password);
mysql_select_db('ak');
$sql='set names utf8';
mysql_query($sql);
$rows=array();
$sql='select * from users ';
$query=mysql_query($sql);
while ($row=mysql_fetch_array($query)){
    $rows[]=$row;
}
mysql_close($con);
?>
