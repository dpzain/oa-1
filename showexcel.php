<?php
$host = 'localhost';
$username = 'root';
$password = 'root';
$con = mysql_connect($host, $username, $password);
mysql_select_db('oa');
$sql = 'set names utf8';
mysql_query($sql);
$sql = "select * from work where id='5'";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
if ($row) {
    $exceldata = json_encode(unserialize($row['content']));
}
mysql_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="handsontable/dist/handsontable.full.js"></script>
    <script src="./public/js/jquery.min.js"></script>
    <script src="public/handsontable/handsontable.full.js"></script>
    <link rel="stylesheet" media="screen" href="public/handsontable/handsontable.full.css">
</head>
<body>
<div style="margin-bottom: 50px">
    <button id="save">保存</button>
    <form action="export.php" method="post">
        <input type="hidden" name="id" value="5">
        <input type="submit" value="导出excel">
    </form>
</div>
<div id="example"></div>
<script>
    function sendData() {
        return <?php echo $exceldata ?>;
    }
    var container = document.getElementById('example');
    var ht = new Handsontable(container, {
        data: sendData(),
        rowHeaders: true,
        colHeaders: true,
        startRows: 5,
        startCols: 5,
        minRows: 30,
        minCols: 20,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 1,
        contextMenu: true,
        colWidths: 150
    });
    $('#save').click(function () {
        var data = ht.getData();
        $.ajax({
            type: 'POST',
            url: 'save.php',
            data: {
                data: data,
            },
            success: function (msg) {
                alert(msg);
            }
        })
    })
</script>
</body>
</html>

