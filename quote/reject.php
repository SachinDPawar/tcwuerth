<html>
<head>

</head>
<body>

<?php
$timeParams=$_GET['WEMPID'];
?>

<form method="POST" action="rejectform.php">
    Reason: <input type="text" name="Reason" value=""><br>
    <input type="text" name="WEMPID" value="<?php echo $timeParams;?> " >
<input type="submit">
    </form>
</body>
</html>